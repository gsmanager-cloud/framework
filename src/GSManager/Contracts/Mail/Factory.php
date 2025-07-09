<?php

namespace GSManager\Contracts\Mail;

interface Factory
{
    /**
     * Get a mailer instance by name.
     *
     * @param  string|null  $name
     * @return \GSManager\Contracts\Mail\Mailer
     */
    public function mailer($name = null);
}
