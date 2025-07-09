<?php

declare(strict_types=1);

namespace GSManager\Container\Attributes;

use Attribute;
use GSManager\Contracts\Container\Container;
use GSManager\Contracts\Container\ContextualAttribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class Tag implements ContextualAttribute
{
    public function __construct(
        public string $tag,
    ) {
    }

    /**
     * Resolve the tag.
     *
     * @param  self  $attribute
     * @param  \GSManager\Contracts\Container\Container  $container
     * @return mixed
     */
    public static function resolve(self $attribute, Container $container)
    {
        return $container->tagged($attribute->tag);
    }
}
