<?php

namespace GSManager\Contracts\Broadcasting;

interface ShouldBroadcast
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \GSManager\Broadcasting\Channel|\GSManager\Broadcasting\Channel[]|string[]|string
     */
    public function broadcastOn();
}
