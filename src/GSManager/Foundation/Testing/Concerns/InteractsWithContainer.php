<?php

namespace GSManager\Foundation\Testing\Concerns;

use Closure;
use GSManager\Foundation\Mix;
use GSManager\Foundation\Vite;
use GSManager\Support\Defer\DeferredCallbackCollection;
use GSManager\Support\Facades\Facade;
use GSManager\Support\HtmlString;
use Mockery;

trait InteractsWithContainer
{
    /**
     * The original Vite handler.
     *
     * @var \GSManager\Foundation\Vite|null
     */
    protected $originalVite;

    /**
     * The original GSManager Mix handler.
     *
     * @var \GSManager\Foundation\Mix|null
     */
    protected $originalMix;

    /**
     * The original deferred callbacks collection.
     *
     * @var \GSManager\Support\Defer\DeferredCallbackCollection|null
     */
    protected $originalDeferredCallbacksCollection;

    /**
     * Register an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  object  $instance
     * @return object
     */
    protected function swap($abstract, $instance)
    {
        return $this->instance($abstract, $instance);
    }

    /**
     * Register an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  object  $instance
     * @return object
     */
    protected function instance($abstract, $instance)
    {
        $this->app->instance($abstract, $instance);

        return $instance;
    }

    /**
     * Mock an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|null  $mock
     * @return \Mockery\MockInterface
     */
    protected function mock($abstract, ?Closure $mock = null)
    {
        return $this->instance($abstract, Mockery::mock(...array_filter(func_get_args())));
    }

    /**
     * Mock a partial instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|null  $mock
     * @return \Mockery\MockInterface
     */
    protected function partialMock($abstract, ?Closure $mock = null)
    {
        return $this->instance($abstract, Mockery::mock(...array_filter(func_get_args()))->makePartial());
    }

    /**
     * Spy an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|null  $mock
     * @return \Mockery\MockInterface
     */
    protected function spy($abstract, ?Closure $mock = null)
    {
        return $this->instance($abstract, Mockery::spy(...array_filter(func_get_args())));
    }

    /**
     * Instruct the container to forget a previously mocked / spied instance of an object.
     *
     * @param  string  $abstract
     * @return $this
     */
    protected function forgetMock($abstract)
    {
        $this->app->forgetInstance($abstract);

        return $this;
    }

    /**
     * Register an empty handler for Vite in the container.
     *
     * @return $this
     */
    protected function withoutVite()
    {
        if ($this->originalVite == null) {
            $this->originalVite = app(Vite::class);
        }

        Facade::clearResolvedInstance(Vite::class);

        $this->swap(Vite::class, new class extends Vite
        {
            public function __invoke($entrypoints, $buildDirectory = null)
            {
                return new HtmlString('');
            }

            public function __call($method, $parameters)
            {
                return '';
            }

            public function __toString()
            {
                return '';
            }

            public function useIntegrityKey($key)
            {
                return $this;
            }

            public function useBuildDirectory($path)
            {
                return $this;
            }

            public function useHotFile($path)
            {
                return $this;
            }

            public function withEntryPoints($entryPoints)
            {
                return $this;
            }

            public function useScriptTagAttributes($attributes)
            {
                return $this;
            }

            public function useStyleTagAttributes($attributes)
            {
                return $this;
            }

            public function usePreloadTagAttributes($attributes)
            {
                return $this;
            }

            public function preloadedAssets()
            {
                return [];
            }

            public function reactRefresh()
            {
                return '';
            }

            public function content($asset, $buildDirectory = null)
            {
                return '';
            }

            public function asset($asset, $buildDirectory = null)
            {
                return '';
            }
        });

        return $this;
    }

    /**
     * Restore Vite in the container.
     *
     * @return $this
     */
    protected function withVite()
    {
        if ($this->originalVite) {
            $this->app->instance(Vite::class, $this->originalVite);
        }

        return $this;
    }

    /**
     * Register an empty handler for GSManager Mix in the container.
     *
     * @return $this
     */
    protected function withoutMix()
    {
        if ($this->originalMix == null) {
            $this->originalMix = app(Mix::class);
        }

        $this->swap(Mix::class, function () {
            return new HtmlString('');
        });

        return $this;
    }

    /**
     * Restore GSManager Mix in the container.
     *
     * @return $this
     */
    protected function withMix()
    {
        if ($this->originalMix) {
            $this->app->instance(Mix::class, $this->originalMix);
        }

        return $this;
    }

    /**
     * Execute deferred functions immediately.
     *
     * @return $this
     */
    protected function withoutDefer()
    {
        if ($this->originalDeferredCallbacksCollection == null) {
            $this->originalDeferredCallbacksCollection = $this->app->make(DeferredCallbackCollection::class);
        }

        $this->swap(DeferredCallbackCollection::class, new class extends DeferredCallbackCollection
        {
            public function offsetSet(mixed $offset, mixed $value): void
            {
                $value();
            }
        });

        return $this;
    }

    /**
     * Restore deferred functions.
     *
     * @return $this
     */
    protected function withDefer()
    {
        if ($this->originalDeferredCallbacksCollection) {
            $this->app->instance(DeferredCallbackCollection::class, $this->originalDeferredCallbacksCollection);
        }

        return $this;
    }
}
