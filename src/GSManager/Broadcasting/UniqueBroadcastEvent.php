<?php

namespace GSManager\Broadcasting;

use GSManager\Container\Container;
use GSManager\Contracts\Cache\Repository;
use GSManager\Contracts\Queue\ShouldBeUnique;

class UniqueBroadcastEvent extends BroadcastEvent implements ShouldBeUnique
{
    /**
     * The unique lock identifier.
     *
     * @var mixed
     */
    public $uniqueId;

    /**
     * The number of seconds the unique lock should be maintained.
     *
     * @var int
     */
    public $uniqueFor;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $event
     */
    public function __construct($event)
    {
        $this->uniqueId = get_class($event);

        if (method_exists($event, 'uniqueId')) {
            $this->uniqueId .= $event->uniqueId();
        } elseif (property_exists($event, 'uniqueId')) {
            $this->uniqueId .= $event->uniqueId;
        }

        if (method_exists($event, 'uniqueFor')) {
            $this->uniqueFor = $event->uniqueFor();
        } elseif (property_exists($event, 'uniqueFor')) {
            $this->uniqueFor = $event->uniqueFor;
        }

        parent::__construct($event);
    }

    /**
     * Resolve the cache implementation that should manage the event's uniqueness.
     *
     * @return \GSManager\Contracts\Cache\Repository
     */
    public function uniqueVia()
    {
        return method_exists($this->event, 'uniqueVia')
            ? $this->event->uniqueVia()
            : Container::getInstance()->make(Repository::class);
    }
}
