<?php

namespace GSManager\Auth\Events;

use GSManager\Queue\SerializesModels;

class Registered
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user  The authenticated user.
     */
    public function __construct(
        public $user,
    ) {
    }
}
