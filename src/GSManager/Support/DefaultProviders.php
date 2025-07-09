<?php

namespace GSManager\Support;

class DefaultProviders
{
    /**
     * The current providers.
     *
     * @var array
     */
    protected $providers;

    /**
     * Create a new default provider collection.
     */
    public function __construct(?array $providers = null)
    {
        $this->providers = $providers ?: [
            \GSManager\Auth\AuthServiceProvider::class,
            \GSManager\Broadcasting\BroadcastServiceProvider::class,
            \GSManager\Bus\BusServiceProvider::class,
            \GSManager\Cache\CacheServiceProvider::class,
            \GSManager\Foundation\Providers\ConsoleSupportServiceProvider::class,
            \GSManager\Concurrency\ConcurrencyServiceProvider::class,
            \GSManager\Cookie\CookieServiceProvider::class,
            \GSManager\Database\DatabaseServiceProvider::class,
            \GSManager\Encryption\EncryptionServiceProvider::class,
            \GSManager\Filesystem\FilesystemServiceProvider::class,
            \GSManager\Foundation\Providers\FoundationServiceProvider::class,
            \GSManager\Hashing\HashServiceProvider::class,
            \GSManager\Mail\MailServiceProvider::class,
            \GSManager\Notifications\NotificationServiceProvider::class,
            \GSManager\Pagination\PaginationServiceProvider::class,
            \GSManager\Auth\Passwords\PasswordResetServiceProvider::class,
            \GSManager\Pipeline\PipelineServiceProvider::class,
            \GSManager\Queue\QueueServiceProvider::class,
            \GSManager\Redis\RedisServiceProvider::class,
            \GSManager\Session\SessionServiceProvider::class,
            \GSManager\Translation\TranslationServiceProvider::class,
            \GSManager\Validation\ValidationServiceProvider::class,
            \GSManager\View\ViewServiceProvider::class,
        ];
    }

    /**
     * Merge the given providers into the provider collection.
     *
     * @param  array  $providers
     * @return static
     */
    public function merge(array $providers)
    {
        $this->providers = array_merge($this->providers, $providers);

        return new static($this->providers);
    }

    /**
     * Replace the given providers with other providers.
     *
     * @param  array  $replacements
     * @return static
     */
    public function replace(array $replacements)
    {
        $current = new Collection($this->providers);

        foreach ($replacements as $from => $to) {
            $key = $current->search($from);

            $current = is_int($key) ? $current->replace([$key => $to]) : $current;
        }

        return new static($current->values()->toArray());
    }

    /**
     * Disable the given providers.
     *
     * @param  array  $providers
     * @return static
     */
    public function except(array $providers)
    {
        return new static((new Collection($this->providers))
            ->reject(fn ($p) => in_array($p, $providers))
            ->values()
            ->toArray());
    }

    /**
     * Convert the provider collection to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->providers;
    }
}
