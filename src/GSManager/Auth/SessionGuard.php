<?php

namespace GSManager\Auth;

use GSManager\Auth\Events\Attempting;
use GSManager\Auth\Events\Authenticated;
use GSManager\Auth\Events\CurrentDeviceLogout;
use GSManager\Auth\Events\Failed;
use GSManager\Auth\Events\Login;
use GSManager\Auth\Events\Logout;
use GSManager\Auth\Events\OtherDeviceLogout;
use GSManager\Auth\Events\Validated;
use GSManager\Contracts\Auth\Authenticatable as AuthenticatableContract;
use GSManager\Contracts\Auth\StatefulGuard;
use GSManager\Contracts\Auth\SupportsBasicAuth;
use GSManager\Contracts\Auth\UserProvider;
use GSManager\Contracts\Cookie\QueueingFactory as CookieJar;
use GSManager\Contracts\Events\Dispatcher;
use GSManager\Contracts\Session\Session;
use GSManager\Support\Arr;
use GSManager\Support\Facades\Hash;
use GSManager\Support\Str;
use GSManager\Support\Timebox;
use GSManager\Support\Traits\Macroable;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SessionGuard implements StatefulGuard, SupportsBasicAuth
{
    use GuardHelpers, Macroable;

    /**
     * The name of the guard. Typically "web".
     *
     * Corresponds to guard name in authentication configuration.
     *
     * @var string
     */
    public readonly string $name;

    /**
     * The user we last attempted to retrieve.
     *
     * @var \GSManager\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;

    /**
     * Indicates if the user was authenticated via a recaller cookie.
     *
     * @var bool
     */
    protected $viaRemember = false;

    /**
     * The number of minutes that the "remember me" cookie should be valid for.
     *
     * @var int
     */
    protected $rememberDuration = 576000;

    /**
     * The session used by the guard.
     *
     * @var \GSManager\Contracts\Session\Session
     */
    protected $session;

    /**
     * The GSManager cookie creator service.
     *
     * @var \GSManager\Contracts\Cookie\QueueingFactory
     */
    protected $cookie;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The event dispatcher instance.
     *
     * @var \GSManager\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * The timebox instance.
     *
     * @var \GSManager\Support\Timebox
     */
    protected $timebox;

    /**
     * The number of microseconds that the timebox should wait for.
     *
     * @var int
     */
    protected $timeboxDuration;

    /**
     * Indicates if passwords should be rehashed on login if needed.
     *
     * @var bool
     */
    protected $rehashOnLogin;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Indicates if a token user retrieval has been attempted.
     *
     * @var bool
     */
    protected $recallAttempted = false;

    /**
     * Create a new authentication guard.
     *
     * @param  string  $name
     * @param  \GSManager\Contracts\Auth\UserProvider  $provider
     * @param  \GSManager\Contracts\Session\Session  $session
     * @param  \Symfony\Component\HttpFoundation\Request|null  $request
     * @param  \GSManager\Support\Timebox|null  $timebox
     * @param  bool  $rehashOnLogin
     * @param  int  $timeboxDuration
     */
    public function __construct(
        $name,
        UserProvider $provider,
        Session $session,
        ?Request $request = null,
        ?Timebox $timebox = null,
        bool $rehashOnLogin = true,
        int $timeboxDuration = 200000,
    ) {
        $this->name = $name;
        $this->session = $session;
        $this->request = $request;
        $this->provider = $provider;
        $this->timebox = $timebox ?: new Timebox;
        $this->rehashOnLogin = $rehashOnLogin;
        $this->timeboxDuration = $timeboxDuration;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \GSManager\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->loggedOut) {
            return;
        }

        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        $id = $this->session->get($this->getName());

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        if (! is_null($id) && $this->user = $this->provider->retrieveById($id)) {
            $this->fireAuthenticatedEvent($this->user);
        }

        // If the user is null, but we decrypt a "recaller" cookie we can attempt to
        // pull the user data on that cookie which serves as a remember cookie on
        // the application. Once we have a user we can return it to the caller.
        if (is_null($this->user) && ! is_null($recaller = $this->recaller())) {
            $this->user = $this->userFromRecaller($recaller);

            if ($this->user) {
                $this->updateSession($this->user->getAuthIdentifier());

                $this->fireLoginEvent($this->user, true);
            }
        }

        return $this->user;
    }

    /**
     * Pull a user from the repository by its "remember me" cookie token.
     *
     * @param  \GSManager\Auth\Recaller  $recaller
     * @return mixed
     */
    protected function userFromRecaller($recaller)
    {
        if (! $recaller->valid() || $this->recallAttempted) {
            return;
        }

        // If the user is null, but we decrypt a "recaller" cookie we can attempt to
        // pull the user data on that cookie which serves as a remember cookie on
        // the application. Once we have a user we can return it to the caller.
        $this->recallAttempted = true;

        $this->viaRemember = ! is_null($user = $this->provider->retrieveByToken(
            $recaller->id(), $recaller->token()
        ));

        return $user;
    }

    /**
     * Get the decrypted recaller cookie for the request.
     *
     * @return \GSManager\Auth\Recaller|null
     */
    protected function recaller()
    {
        if (is_null($this->request)) {
            return;
        }

        if ($recaller = $this->request->cookies->get($this->getRecallerName())) {
            return new Recaller($recaller);
        }
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function id()
    {
        if ($this->loggedOut) {
            return;
        }

        return $this->user()
            ? $this->user()->getAuthIdentifier()
            : $this->session->get($this->getName());
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function once(array $credentials = [])
    {
        $this->fireAttemptEvent($credentials);

        if ($this->validate($credentials)) {
            $this->rehashPasswordIfRequired($this->lastAttempted, $credentials);

            $this->setUser($this->lastAttempted);

            return true;
        }

        $this->fireFailedEvent($this->lastAttempted, $credentials);

        return false;
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed  $id
     * @return \GSManager\Contracts\Auth\Authenticatable|false
     */
    public function onceUsingId($id)
    {
        if (! is_null($user = $this->provider->retrieveById($id))) {
            $this->setUser($user);

            return $user;
        }

        return false;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return $this->timebox->call(function ($timebox) use ($credentials) {
            $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

            $validated = $this->hasValidCredentials($user, $credentials);

            if ($validated) {
                $timebox->returnEarly();
            }

            return $validated;
        }, $this->timeboxDuration);
    }

    /**
     * Attempt to authenticate using HTTP Basic Auth.
     *
     * @param  string  $field
     * @param  array  $extraConditions
     * @return \Symfony\Component\HttpFoundation\Response|null
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function basic($field = 'email', $extraConditions = [])
    {
        if ($this->check()) {
            return;
        }

        // If a username is set on the HTTP basic request, we will return out without
        // interrupting the request lifecycle. Otherwise, we'll need to generate a
        // request indicating that the given credentials were invalid for login.
        if ($this->attemptBasic($this->getRequest(), $field, $extraConditions)) {
            return;
        }

        return $this->failedBasicResponse();
    }

    /**
     * Perform a stateless HTTP Basic login attempt.
     *
     * @param  string  $field
     * @param  array  $extraConditions
     * @return \Symfony\Component\HttpFoundation\Response|null
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    public function onceBasic($field = 'email', $extraConditions = [])
    {
        $credentials = $this->basicCredentials($this->getRequest(), $field);

        if (! $this->once(array_merge($credentials, $extraConditions))) {
            return $this->failedBasicResponse();
        }
    }

    /**
     * Attempt to authenticate using basic authentication.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  string  $field
     * @param  array  $extraConditions
     * @return bool
     */
    protected function attemptBasic(Request $request, $field, $extraConditions = [])
    {
        if (! $request->getUser()) {
            return false;
        }

        return $this->attempt(array_merge(
            $this->basicCredentials($request, $field), $extraConditions
        ));
    }

    /**
     * Get the credential array for an HTTP Basic request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  string  $field
     * @return array
     */
    protected function basicCredentials(Request $request, $field)
    {
        return [$field => $request->getUser(), 'password' => $request->getPassword()];
    }

    /**
     * Get the response for basic authentication.
     *
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     */
    protected function failedBasicResponse()
    {
        throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool  $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        return $this->timebox->call(function ($timebox) use ($credentials, $remember) {
            $this->fireAttemptEvent($credentials, $remember);

            $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

            // If an implementation of UserInterface was returned, we'll ask the provider
            // to validate the user against the given credentials, and if they are in
            // fact valid we'll log the users into the application and return true.
            if ($this->hasValidCredentials($user, $credentials)) {
                $this->rehashPasswordIfRequired($user, $credentials);

                $this->login($user, $remember);

                $timebox->returnEarly();

                return true;
            }

            // If the authentication attempt fails we will fire an event so that the user
            // may be notified of any suspicious attempts to access their account from
            // an unrecognized user. A developer may listen to this event as needed.
            $this->fireFailedEvent($user, $credentials);

            return false;
        }, $this->timeboxDuration);
    }

    /**
     * Attempt to authenticate a user with credentials and additional callbacks.
     *
     * @param  array  $credentials
     * @param  array|callable|null  $callbacks
     * @param  bool  $remember
     * @return bool
     */
    public function attemptWhen(array $credentials = [], $callbacks = null, $remember = false)
    {
        return $this->timebox->call(function ($timebox) use ($credentials, $callbacks, $remember) {
            $this->fireAttemptEvent($credentials, $remember);

            $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

            // This method does the exact same thing as attempt, but also executes callbacks after
            // the user is retrieved and validated. If one of the callbacks returns falsy we do
            // not login the user. Instead, we will fail the specific authentication attempt.
            if ($this->hasValidCredentials($user, $credentials) && $this->shouldLogin($callbacks, $user)) {
                $this->rehashPasswordIfRequired($user, $credentials);

                $this->login($user, $remember);

                $timebox->returnEarly();

                return true;
            }

            $this->fireFailedEvent($user, $credentials);

            return false;
        }, $this->timeboxDuration);
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        $validated = ! is_null($user) && $this->provider->validateCredentials($user, $credentials);

        if ($validated) {
            $this->fireValidatedEvent($user);
        }

        return $validated;
    }

    /**
     * Determine if the user should login by executing the given callbacks.
     *
     * @param  array|callable|null  $callbacks
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return bool
     */
    protected function shouldLogin($callbacks, AuthenticatableContract $user)
    {
        foreach (Arr::wrap($callbacks) as $callback) {
            if (! $callback($user, $this)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Rehash the user's password if enabled and required.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return void
     */
    protected function rehashPasswordIfRequired(AuthenticatableContract $user, #[\SensitiveParameter] array $credentials)
    {
        if ($this->rehashOnLogin) {
            $this->provider->rehashPasswordIfRequired($user, $credentials);
        }
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed  $id
     * @param  bool  $remember
     * @return \GSManager\Contracts\Auth\Authenticatable|false
     */
    public function loginUsingId($id, $remember = false)
    {
        if (! is_null($user = $this->provider->retrieveById($id))) {
            $this->login($user, $remember);

            return $user;
        }

        return false;
    }

    /**
     * Log a user into the application.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(AuthenticatableContract $user, $remember = false)
    {
        $this->updateSession($user->getAuthIdentifier());

        // If the user should be permanently "remembered" by the application we will
        // queue a permanent cookie that contains the encrypted copy of the user
        // identifier. We will then decrypt this later to retrieve the users.
        if ($remember) {
            $this->ensureRememberTokenIsSet($user);

            $this->queueRecallerCookie($user);
        }

        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        $this->fireLoginEvent($user, $remember);

        $this->setUser($user);
    }

    /**
     * Update the session with the given ID.
     *
     * @param  string  $id
     * @return void
     */
    protected function updateSession($id)
    {
        $this->session->put($this->getName(), $id);

        $this->session->migrate(true);
    }

    /**
     * Create a new "remember me" token for the user if one doesn't already exist.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function ensureRememberTokenIsSet(AuthenticatableContract $user)
    {
        if (empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }
    }

    /**
     * Queue the recaller cookie into the cookie jar.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function queueRecallerCookie(AuthenticatableContract $user)
    {
        $this->getCookieJar()->queue($this->createRecaller(
            $user->getAuthIdentifier().'|'.$user->getRememberToken().'|'.$user->getAuthPassword()
        ));
    }

    /**
     * Create a "remember me" cookie for a given ID.
     *
     * @param  string  $value
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    protected function createRecaller($value)
    {
        return $this->getCookieJar()->make($this->getRecallerName(), $value, $this->getRememberDuration());
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $user = $this->user();

        $this->clearUserDataFromStorage();

        if (! is_null($this->user) && ! empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }

        // If we have an event dispatcher instance, we can fire off the logout event
        // so any further processing can be done. This allows the developer to be
        // listening for anytime a user signs out of this application manually.
        $this->events?->dispatch(new Logout($this->name, $user));

        // Once we have fired the logout event we will clear the users out of memory
        // so they are no longer available as the user is no longer considered as
        // being signed into this application and should not be available here.
        $this->user = null;

        $this->loggedOut = true;
    }

    /**
     * Log the user out of the application on their current device only.
     *
     * This method does not cycle the "remember" token.
     *
     * @return void
     */
    public function logoutCurrentDevice()
    {
        $user = $this->user();

        $this->clearUserDataFromStorage();

        // If we have an event dispatcher instance, we can fire off the logout event
        // so any further processing can be done. This allows the developer to be
        // listening for anytime a user signs out of this application manually.
        $this->events?->dispatch(new CurrentDeviceLogout($this->name, $user));

        // Once we have fired the logout event we will clear the users out of memory
        // so they are no longer available as the user is no longer considered as
        // being signed into this application and should not be available here.
        $this->user = null;

        $this->loggedOut = true;
    }

    /**
     * Remove the user data from the session and cookies.
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        $this->session->remove($this->getName());

        $this->getCookieJar()->unqueue($this->getRecallerName());

        if (! is_null($this->recaller())) {
            $this->getCookieJar()->queue(
                $this->getCookieJar()->forget($this->getRecallerName())
            );
        }
    }

    /**
     * Refresh the "remember me" token for the user.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function cycleRememberToken(AuthenticatableContract $user)
    {
        $user->setRememberToken($token = Str::random(60));

        $this->provider->updateRememberToken($user, $token);
    }

    /**
     * Invalidate other sessions for the current user.
     *
     * The application must be using the AuthenticateSession middleware.
     *
     * @param  string  $password
     * @return \GSManager\Contracts\Auth\Authenticatable|null
     *
     * @throws \GSManager\Auth\AuthenticationException
     */
    public function logoutOtherDevices($password)
    {
        if (! $this->user()) {
            return;
        }

        $result = $this->rehashUserPasswordForDeviceLogout($password);

        if ($this->recaller() ||
            $this->getCookieJar()->hasQueued($this->getRecallerName())) {
            $this->queueRecallerCookie($this->user());
        }

        $this->fireOtherDeviceLogoutEvent($this->user());

        return $result;
    }

    /**
     * Rehash the current user's password for logging out other devices via AuthenticateSession.
     *
     * @param  string  $password
     * @return \GSManager\Contracts\Auth\Authenticatable|null
     *
     * @throws \InvalidArgumentException
     */
    protected function rehashUserPasswordForDeviceLogout($password)
    {
        $user = $this->user();

        if (! Hash::check($password, $user->getAuthPassword())) {
            throw new InvalidArgumentException('The given password does not match the current password.');
        }

        $this->provider->rehashPasswordIfRequired(
            $user, ['password' => $password], force: true
        );
    }

    /**
     * Register an authentication attempt event listener.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function attempting($callback)
    {
        $this->events?->listen(Events\Attempting::class, $callback);
    }

    /**
     * Fire the attempt event with the arguments.
     *
     * @param  array  $credentials
     * @param  bool  $remember
     * @return void
     */
    protected function fireAttemptEvent(array $credentials, $remember = false)
    {
        $this->events?->dispatch(new Attempting($this->name, $credentials, $remember));
    }

    /**
     * Fires the validated event if the dispatcher is set.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function fireValidatedEvent($user)
    {
        $this->events?->dispatch(new Validated($this->name, $user));
    }

    /**
     * Fire the login event if the dispatcher is set.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    protected function fireLoginEvent($user, $remember = false)
    {
        $this->events?->dispatch(new Login($this->name, $user, $remember));
    }

    /**
     * Fire the authenticated event if the dispatcher is set.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function fireAuthenticatedEvent($user)
    {
        $this->events?->dispatch(new Authenticated($this->name, $user));
    }

    /**
     * Fire the other device logout event if the dispatcher is set.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function fireOtherDeviceLogoutEvent($user)
    {
        $this->events?->dispatch(new OtherDeviceLogout($this->name, $user));
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable|null  $user
     * @param  array  $credentials
     * @return void
     */
    protected function fireFailedEvent($user, array $credentials)
    {
        $this->events?->dispatch(new Failed($this->name, $user, $credentials));
    }

    /**
     * Get the last user we attempted to authenticate.
     *
     * @return \GSManager\Contracts\Auth\Authenticatable
     */
    public function getLastAttempted()
    {
        return $this->lastAttempted;
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_'.$this->name.'_'.sha1(static::class);
    }

    /**
     * Get the name of the cookie used to store the "recaller".
     *
     * @return string
     */
    public function getRecallerName()
    {
        return 'remember_'.$this->name.'_'.sha1(static::class);
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember()
    {
        return $this->viaRemember;
    }

    /**
     * Get the number of minutes the remember me cookie should be valid for.
     *
     * @return int
     */
    protected function getRememberDuration()
    {
        return $this->rememberDuration;
    }

    /**
     * Set the number of minutes the remember me cookie should be valid for.
     *
     * @param  int  $minutes
     * @return $this
     */
    public function setRememberDuration($minutes)
    {
        $this->rememberDuration = $minutes;

        return $this;
    }

    /**
     * Get the cookie creator instance used by the guard.
     *
     * @return \GSManager\Contracts\Cookie\QueueingFactory
     *
     * @throws \RuntimeException
     */
    public function getCookieJar()
    {
        if (! isset($this->cookie)) {
            throw new RuntimeException('Cookie jar has not been set.');
        }

        return $this->cookie;
    }

    /**
     * Set the cookie creator instance used by the guard.
     *
     * @param  \GSManager\Contracts\Cookie\QueueingFactory  $cookie
     * @return void
     */
    public function setCookieJar(CookieJar $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \GSManager\Contracts\Events\Dispatcher
     */
    public function getDispatcher()
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \GSManager\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Get the session store used by the guard.
     *
     * @return \GSManager\Contracts\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Return the currently cached user.
     *
     * @return \GSManager\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the current user.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable  $user
     * @return $this
     */
    public function setUser(AuthenticatableContract $user)
    {
        $this->user = $user;

        $this->loggedOut = false;

        $this->fireAuthenticatedEvent($user);

        return $this;
    }

    /**
     * Get the current request instance.
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request ?: Request::createFromGlobals();
    }

    /**
     * Set the current request instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the timebox instance used by the guard.
     *
     * @return \GSManager\Support\Timebox
     */
    public function getTimebox()
    {
        return $this->timebox;
    }
}
