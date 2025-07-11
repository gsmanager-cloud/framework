<?php

namespace GSManager\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseEloquentBuilder
{
    /**
     * Create a new attribute instance.
     *
     * @param  class-string<\GSManager\Database\Eloquent\Builder>  $builderClass
     */
    public function __construct(public string $builderClass)
    {
    }
}
