<?php

namespace GSManager\Contracts\Database\Eloquent;

use GSManager\Database\Eloquent\Model;

interface CastsInboundAttributes
{
    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \GSManager\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes);
}
