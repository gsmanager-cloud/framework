<?php

namespace GSManager\Foundation\Exceptions\Renderer;

use GSManager\Contracts\Events\Dispatcher;
use GSManager\Database\Events\QueryExecuted;
use GSManager\Queue\Events\JobProcessed;
use GSManager\Queue\Events\JobProcessing;
use GSManager\Octane\Events\RequestReceived;
use GSManager\Octane\Events\RequestTerminated;
use GSManager\Octane\Events\TaskReceived;
use GSManager\Octane\Events\TickReceived;

class Listener
{
    /**
     * The queries that have been executed.
     *
     * @var array<int, array{connectionName: string, time: float, sql: string, bindings: array}>
     */
    protected $queries = [];

    /**
     * Register the appropriate listeners on the given event dispatcher.
     *
     * @param  \GSManager\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function registerListeners(Dispatcher $events)
    {
        $events->listen(QueryExecuted::class, $this->onQueryExecuted(...));

        $events->listen([JobProcessing::class, JobProcessed::class], function () {
            $this->queries = [];
        });

        if (isset($_SERVER['GSMANAGER_OCTANE'])) {
            $events->listen([RequestReceived::class, TaskReceived::class, TickReceived::class, RequestTerminated::class], function () {
                $this->queries = [];
            });
        }
    }

    /**
     * Returns the queries that have been executed.
     *
     * @return array<int, array{sql: string, time: float}>
     */
    public function queries()
    {
        return $this->queries;
    }

    /**
     * Listens for the query executed event.
     *
     * @param  \GSManager\Database\Events\QueryExecuted  $event
     * @return void
     */
    public function onQueryExecuted(QueryExecuted $event)
    {
        if (count($this->queries) === 100) {
            return;
        }

        $this->queries[] = [
            'connectionName' => $event->connectionName,
            'time' => $event->time,
            'sql' => $event->sql,
            'bindings' => $event->bindings,
        ];
    }
}
