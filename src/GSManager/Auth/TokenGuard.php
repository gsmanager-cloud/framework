<?php

namespace GSManager\Auth;

use GSManager\Contracts\Auth\Guard;
use GSManager\Contracts\Auth\UserProvider;
use GSManager\Http\Request;
use GSManager\Support\Traits\Macroable;

class TokenGuard implements Guard
{
    use GuardHelpers, Macroable;

    /**
     * The request instance.
     *
     * @var \GSManager\Http\Request
     */
    protected $request;

    /**
     * The name of the query string item from the request containing the API token.
     *
     * @var string
     */
    protected $inputKey;

    /**
     * The name of the token "column" in persistent storage.
     *
     * @var string
     */
    protected $storageKey;

    /**
     * Indicates if the API token is hashed in storage.
     *
     * @var bool
     */
    protected $hash = false;

    /**
     * Create a new authentication guard.
     *
     * @param  \GSManager\Contracts\Auth\UserProvider  $provider
     * @param  \GSManager\Http\Request  $request
     * @param  string  $inputKey
     * @param  string  $storageKey
     * @param  bool  $hash
     */
    public function __construct(
        UserProvider $provider,
        Request $request,
        $inputKey = 'api_token',
        $storageKey = 'api_token',
        $hash = false,
    ) {
        $this->hash = $hash;
        $this->request = $request;
        $this->provider = $provider;
        $this->inputKey = $inputKey;
        $this->storageKey = $storageKey;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \GSManager\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $token = $this->getTokenForRequest();

        if (! empty($token)) {
            $user = $this->provider->retrieveByCredentials([
                $this->storageKey => $this->hash ? hash('sha256', $token) : $token,
            ]);
        }

        return $this->user = $user;
    }

    /**
     * Get the token for the current request.
     *
     * @return string|null
     */
    public function getTokenForRequest()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        if (empty($token)) {
            $token = $this->request->getPassword();
        }

        return $token;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials[$this->inputKey])) {
            return false;
        }

        $credentials = [$this->storageKey => $credentials[$this->inputKey]];

        if ($this->provider->retrieveByCredentials($credentials)) {
            return true;
        }

        return false;
    }

    /**
     * Set the current request instance.
     *
     * @param  \GSManager\Http\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}
