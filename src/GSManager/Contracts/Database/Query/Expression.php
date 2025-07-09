<?php

namespace GSManager\Contracts\Database\Query;

use GSManager\Database\Grammar;

interface Expression
{
    /**
     * Get the value of the expression.
     *
     * @param  \GSManager\Database\Grammar  $grammar
     * @return string|int|float
     */
    public function getValue(Grammar $grammar);
}
