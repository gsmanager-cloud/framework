<?php

namespace GSManager\Contracts\Auth;

interface PasswordBrokerFactory
{
    /**
     * Get a password broker instance by name.
     *
     * @param  string|null  $name
     * @return \GSManager\Contracts\Auth\PasswordBroker
     */
    public function broker($name = null);
}
