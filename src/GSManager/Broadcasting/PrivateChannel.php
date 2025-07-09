<?php

namespace GSManager\Broadcasting;

use GSManager\Contracts\Broadcasting\HasBroadcastChannel;

class PrivateChannel extends Channel
{
    /**
     * Create a new channel instance.
     *
     * @param  \GSManager\Contracts\Broadcasting\HasBroadcastChannel|string  $name
     */
    public function __construct($name)
    {
        $name = $name instanceof HasBroadcastChannel ? $name->broadcastChannel() : $name;

        parent::__construct('private-'.$name);
    }
}
