<?php

namespace GSManager\Container\Attributes;

use Attribute;
use GSManager\Contracts\Container\Container;
use GSManager\Contracts\Container\ContextualAttribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Database implements ContextualAttribute
{
    /**
     * Create a new class instance.
     */
    public function __construct(public ?string $connection = null)
    {
    }

    /**
     * Resolve the database connection.
     *
     * @param  self  $attribute
     * @param  \GSManager\Contracts\Container\Container  $container
     * @return \GSManager\Database\Connection
     */
    public static function resolve(self $attribute, Container $container)
    {
        return $container->make('db')->connection($attribute->connection);
    }
}
