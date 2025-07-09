<?php

namespace GSManager\Contracts\Database\Eloquent;

use GSManager\Database\Eloquent\Model;

interface ComparesCastableAttributes
{
    /**
     * Determine if the given values are equal.
     *
     * @param  \GSManager\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $firstValue
     * @param  mixed  $secondValue
     * @return bool
     */
    public function compare(Model $model, string $key, mixed $firstValue, mixed $secondValue);
}
