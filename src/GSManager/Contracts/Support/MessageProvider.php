<?php

namespace GSManager\Contracts\Support;

interface MessageProvider
{
    /**
     * Get the messages for the instance.
     *
     * @return \GSManager\Contracts\Support\MessageBag
     */
    public function getMessageBag();
}
