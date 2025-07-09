<?php

namespace GSManager\Auth\Events;

use GSManager\Queue\SerializesModels;

class Verified
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Contracts\Auth\MustVerifyEmail  $user  The verified user.
     */
    public function __construct(
        public $user,
    ) {
    }
}
