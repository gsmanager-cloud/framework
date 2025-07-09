<?php

namespace GSManager\Database\Eloquent\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class UseFactory
{
    /**
     * Create a new attribute instance.
     *
     * @param  class-string<\GSManager\Database\Eloquent\Factories\Factory>  $factoryClass
     */
    public function __construct(public string $factoryClass)
    {
    }
}
