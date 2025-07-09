<?php

namespace GSManager\Auth\Events;

use GSManager\Queue\SerializesModels;

class OtherDeviceLogout
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $guard  The authentication guard name.
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user  \GSManager\Contracts\Auth\Authenticatable
     */
    public function __construct(
        public $guard,
        public $user,
    ) {
    }
}
