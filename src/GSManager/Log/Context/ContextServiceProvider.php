<?php

namespace GSManager\Log\Context;

use GSManager\Contracts\Log\ContextLogProcessor as ContextLogProcessorContract;
use GSManager\Queue\Events\JobProcessing;
use GSManager\Queue\Queue;
use GSManager\Support\Facades\Context;
use GSManager\Support\ServiceProvider;

class ContextServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(Repository::class);

        $this->app->bind(ContextLogProcessorContract::class, fn () => new ContextLogProcessor());
    }

    /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::createPayloadUsing(function ($connection, $queue, $payload) {
            /** @phpstan-ignore staticMethod.notFound */
            $context = Context::dehydrate();

            return $context === null ? $payload : [
                ...$payload,
                'gsmanager:log:context' => $context,
            ];
        });

        $this->app['events']->listen(function (JobProcessing $event) {
            /** @phpstan-ignore staticMethod.notFound */
            Context::hydrate($event->job->payload()['gsmanager:log:context'] ?? null);
        });
    }
}
