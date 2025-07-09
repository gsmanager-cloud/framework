<?php

namespace GSManager\Log\Context\Events;

class ContextHydrated
{
    /**
     * The context instance.
     *
     * @var \GSManager\Log\Context\Repository
     */
    public $context;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Log\Context\Repository  $context
     */
    public function __construct($context)
    {
        $this->context = $context;
    }
}
