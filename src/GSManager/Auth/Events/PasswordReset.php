<?php

namespace GSManager\Auth\Events;

use GSManager\Queue\SerializesModels;

class PasswordReset
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user  The user.
     */
    public function __construct(
        public $user,
    ) {
    }
}
