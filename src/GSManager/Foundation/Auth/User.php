<?php

namespace GSManager\Foundation\Auth;

use GSManager\Auth\Authenticatable;
use GSManager\Auth\MustVerifyEmail;
use GSManager\Auth\Passwords\CanResetPassword;
use GSManager\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use GSManager\Contracts\Auth\Authenticatable as AuthenticatableContract;
use GSManager\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use GSManager\Database\Eloquent\Model;
use GSManager\Foundation\Auth\Access\Authorizable;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
}
