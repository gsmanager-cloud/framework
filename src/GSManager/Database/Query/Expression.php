<?php

namespace GSManager\Database\Query;

use GSManager\Contracts\Database\Query\Expression as ExpressionContract;
use GSManager\Database\Grammar;

/**
 * @template TValue of string|int|float
 */
class Expression implements ExpressionContract
{
    /**
     * Create a new raw query expression.
     *
     * @param  TValue  $value
     */
    public function __construct(
        protected $value
    ) {
    }

    /**
     * Get the value of the expression.
     *
     * @param  \GSManager\Database\Grammar  $grammar
     * @return TValue
     */
    public function getValue(Grammar $grammar)
    {
        return $this->value;
    }
}
