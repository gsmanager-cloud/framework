<?php

namespace GSManager\Database\Eloquent\Casts;

use GSManager\Contracts\Database\Eloquent\Castable;
use GSManager\Contracts\Database\Eloquent\CastsAttributes;
use GSManager\Support\Fluent;

class AsFluent implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return \GSManager\Contracts\Database\Eloquent\CastsAttributes<\GSManager\Support\Fluent, string>
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                return isset($value) ? new Fluent(Json::decode($value)) : null;
            }

            public function set($model, $key, $value, $attributes)
            {
                return isset($value) ? [$key => Json::encode($value)] : null;
            }
        };
    }
}
