<?php

namespace GSManager\Auth\Events;

use GSManager\Queue\SerializesModels;

class PasswordResetLinkSent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Contracts\Auth\CanResetPassword  $user  The user instance.
     */
    public function __construct(
        public $user,
    ) {
    }
}
