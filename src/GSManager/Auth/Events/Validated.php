<?php

namespace GSManager\Auth\Events;

use GSManager\Queue\SerializesModels;

class Validated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $guard  The authentication guard name.
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user  The user retrieved and validated from the User Provider.
     */
    public function __construct(
        public $guard,
        public $user,
    ) {
    }
}
