<?php

namespace GSManager\Foundation\Providers;

use GSManager\Console\Events\CommandFinished;
use GSManager\Console\Scheduling\Schedule;
use GSManager\Contracts\Console\Kernel as ConsoleKernel;
use GSManager\Contracts\Container\Container;
use GSManager\Contracts\Events\Dispatcher;
use GSManager\Contracts\Foundation\Application;
use GSManager\Contracts\Foundation\MaintenanceMode as MaintenanceModeContract;
use GSManager\Contracts\View\Factory;
use GSManager\Database\ConnectionInterface;
use GSManager\Database\Grammar;
use GSManager\Foundation\Console\CliDumper;
use GSManager\Foundation\Exceptions\Renderer\Listener;
use GSManager\Foundation\Exceptions\Renderer\Mappers\BladeMapper;
use GSManager\Foundation\Exceptions\Renderer\Renderer;
use GSManager\Foundation\Http\HtmlDumper;
use GSManager\Foundation\MaintenanceModeManager;
use GSManager\Foundation\Precognition;
use GSManager\Foundation\Vite;
use GSManager\Http\Client\Factory as HttpFactory;
use GSManager\Http\Request;
use GSManager\Log\Events\MessageLogged;
use GSManager\Queue\Events\JobAttempted;
use GSManager\Support\AggregateServiceProvider;
use GSManager\Support\Defer\DeferredCallbackCollection;
use GSManager\Support\Facades\URL;
use GSManager\Support\Uri;
use GSManager\Testing\LoggedExceptionCollection;
use GSManager\Testing\ParallelTestingServiceProvider;
use GSManager\Validation\ValidationException;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\VarDumper\Caster\StubCaster;
use Symfony\Component\VarDumper\Cloner\AbstractCloner;

class FoundationServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        FormRequestServiceProvider::class,
        ParallelTestingServiceProvider::class,
    ];

    /**
     * The singletons to register into the container.
     *
     * @var array
     */
    public $singletons = [
        HttpFactory::class => HttpFactory::class,
        Vite::class => Vite::class,
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Exceptions/views' => $this->app->resourcePath('views/errors/'),
            ], 'gsmanager-errors');
        }

        if ($this->app->hasDebugModeEnabled()) {
            $this->app->make(Listener::class)->registerListeners(
                $this->app->make(Dispatcher::class)
            );
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->registerConsoleSchedule();
        $this->registerDumper();
        $this->registerRequestValidation();
        $this->registerRequestSignatureValidation();
        $this->registerUriUrlGeneration();
        $this->registerDeferHandler();
        $this->registerExceptionTracking();
        $this->registerExceptionRenderer();
        $this->registerMaintenanceModeManager();
    }

    /**
     * Register the console schedule implementation.
     *
     * @return void
     */
    public function registerConsoleSchedule()
    {
        $this->app->singleton(Schedule::class, function ($app) {
            return $app->make(ConsoleKernel::class)->resolveConsoleSchedule();
        });
    }

    /**
     * Register a var dumper (with source) to debug variables.
     *
     * @return void
     */
    public function registerDumper()
    {
        AbstractCloner::$defaultCasters[ConnectionInterface::class] ??= [StubCaster::class, 'cutInternals'];
        AbstractCloner::$defaultCasters[Container::class] ??= [StubCaster::class, 'cutInternals'];
        AbstractCloner::$defaultCasters[Dispatcher::class] ??= [StubCaster::class, 'cutInternals'];
        AbstractCloner::$defaultCasters[Factory::class] ??= [StubCaster::class, 'cutInternals'];
        AbstractCloner::$defaultCasters[Grammar::class] ??= [StubCaster::class, 'cutInternals'];

        $basePath = $this->app->basePath();

        $compiledViewPath = $this->app['config']->get('view.compiled');

        $format = $_SERVER['VAR_DUMPER_FORMAT'] ?? null;

        match (true) {
            'html' == $format => HtmlDumper::register($basePath, $compiledViewPath),
            'cli' == $format => CliDumper::register($basePath, $compiledViewPath),
            'server' == $format => null,
            $format && 'tcp' == parse_url($format, PHP_URL_SCHEME) => null,
            default => in_array(PHP_SAPI, ['cli', 'phpdbg']) ? CliDumper::register($basePath, $compiledViewPath) : HtmlDumper::register($basePath, $compiledViewPath),
        };
    }

    /**
     * Register the "validate" macro on the request.
     *
     * @return void
     */
    public function registerRequestValidation()
    {
        Request::macro('validate', function (array $rules, ...$params) {
            return tap(validator($this->all(), $rules, ...$params), function ($validator) {
                if ($this->isPrecognitive()) {
                    $validator->after(Precognition::afterValidationHook($this))
                        ->setRules(
                            $this->filterPrecognitiveRules($validator->getRulesWithoutPlaceholders())
                        );
                }
            })->validate();
        });

        Request::macro('validateWithBag', function (string $errorBag, array $rules, ...$params) {
            try {
                return $this->validate($rules, ...$params);
            } catch (ValidationException $e) {
                $e->errorBag = $errorBag;

                throw $e;
            }
        });
    }

    /**
     * Register the "hasValidSignature" macro on the request.
     *
     * @return void
     */
    public function registerRequestSignatureValidation()
    {
        Request::macro('hasValidSignature', function ($absolute = true) {
            return URL::hasValidSignature($this, $absolute);
        });

        Request::macro('hasValidRelativeSignature', function () {
            return URL::hasValidSignature($this, $absolute = false);
        });

        Request::macro('hasValidSignatureWhileIgnoring', function ($ignoreQuery = [], $absolute = true) {
            return URL::hasValidSignature($this, $absolute, $ignoreQuery);
        });

        Request::macro('hasValidRelativeSignatureWhileIgnoring', function ($ignoreQuery = []) {
            return URL::hasValidSignature($this, $absolute = false, $ignoreQuery);
        });
    }

    /**
     * Register the URL resolver for the URI generator.
     *
     * @return void
     */
    protected function registerUriUrlGeneration()
    {
        Uri::setUrlGeneratorResolver(fn () => app('url'));
    }

    /**
     * Register the "defer" function termination handler.
     *
     * @return void
     */
    protected function registerDeferHandler()
    {
        $this->app->scoped(DeferredCallbackCollection::class);

        $this->app['events']->listen(function (CommandFinished $event) {
            app(DeferredCallbackCollection::class)->invokeWhen(fn ($callback) => app()->runningInConsole() && ($event->exitCode === 0 || $callback->always)
            );
        });

        $this->app['events']->listen(function (JobAttempted $event) {
            app(DeferredCallbackCollection::class)->invokeWhen(fn ($callback) => $event->connectionName !== 'sync' && ($event->successful() || $callback->always)
            );
        });
    }

    /**
     * Register an event listener to track logged exceptions.
     *
     * @return void
     */
    protected function registerExceptionTracking()
    {
        if (! $this->app->runningUnitTests()) {
            return;
        }

        $this->app->instance(
            LoggedExceptionCollection::class,
            new LoggedExceptionCollection
        );

        $this->app->make('events')->listen(MessageLogged::class, function ($event) {
            if (isset($event->context['exception'])) {
                $this->app->make(LoggedExceptionCollection::class)
                    ->push($event->context['exception']);
            }
        });
    }

    /**
     * Register the exceptions renderer.
     *
     * @return void
     */
    protected function registerExceptionRenderer()
    {
        $this->loadViewsFrom(__DIR__.'/../Exceptions/views', 'gsmanager-exceptions');

        if (! $this->app->hasDebugModeEnabled()) {
            return;
        }

        $this->loadViewsFrom(__DIR__.'/../resources/exceptions/renderer', 'gsmanager-exceptions-renderer');

        $this->app->singleton(Renderer::class, function (Application $app) {
            $errorRenderer = new HtmlErrorRenderer(
                $app['config']->get('app.debug'),
            );

            return new Renderer(
                $app->make(Factory::class),
                $app->make(Listener::class),
                $errorRenderer,
                $app->make(BladeMapper::class),
                $app->basePath(),
            );
        });

        $this->app->singleton(Listener::class);
    }

    /**
     * Register the maintenance mode manager service.
     *
     * @return void
     */
    public function registerMaintenanceModeManager()
    {
        $this->app->singleton(MaintenanceModeManager::class);

        $this->app->bind(
            MaintenanceModeContract::class,
            fn () => $this->app->make(MaintenanceModeManager::class)->driver()
        );
    }
}
