<?php

namespace GSManager\Auth\Events;

use GSManager\Queue\SerializesModels;

class Authenticated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $guard  The authentication guard name.
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user  The authenticated user.
     */
    public function __construct(
        public $guard,
        public $user,
    ) {
    }
}
