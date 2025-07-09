<?php

namespace GSManager\Support\Facades;

/**
 * @method static \GSManager\Validation\Validator make(array $data, array $rules, array $messages = [], array $attributes = [])
 * @method static array validate(array $data, array $rules, array $messages = [], array $attributes = [])
 * @method static void extend(string $rule, \Closure|string $extension, string|null $message = null)
 * @method static void extendImplicit(string $rule, \Closure|string $extension, string|null $message = null)
 * @method static void extendDependent(string $rule, \Closure|string $extension, string|null $message = null)
 * @method static void replacer(string $rule, \Closure|string $replacer)
 * @method static void includeUnvalidatedArrayKeys()
 * @method static void excludeUnvalidatedArrayKeys()
 * @method static void resolver(\Closure $resolver)
 * @method static \GSManager\Contracts\Translation\Translator getTranslator()
 * @method static \GSManager\Validation\PresenceVerifierInterface getPresenceVerifier()
 * @method static void setPresenceVerifier(\GSManager\Validation\PresenceVerifierInterface $presenceVerifier)
 * @method static \GSManager\Contracts\Container\Container|null getContainer()
 * @method static \GSManager\Validation\Factory setContainer(\GSManager\Contracts\Container\Container $container)
 *
 * @see \GSManager\Validation\Factory
 */
class Validator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'validator';
    }
}
