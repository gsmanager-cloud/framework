<?php

namespace GSManager\Contracts\Support;

interface DeferringDisplayableValue
{
    /**
     * Resolve the displayable value that the class is deferring.
     *
     * @return \GSManager\Contracts\Support\Htmlable|string
     */
    public function resolveDisplayableValue();
}
