<?php

namespace GSManager\Foundation;

use GSManager\Database\Migrations\Migrator;
use GSManager\Foundation\Bootstrap\HandleExceptions;
use GSManager\Foundation\Bootstrap\LoadConfiguration;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\SocketHandler;
use PDO;

class Cloud
{
    /**
     * Handle a bootstrapper that is bootstrapping.
     */
    public static function bootstrapperBootstrapping(Application $app, string $bootstrapper): void
    {
        //
    }

    /**
     * Handle a bootstrapper that has bootstrapped.
     */
    public static function bootstrapperBootstrapped(Application $app, string $bootstrapper): void
    {
        (match ($bootstrapper) {
            LoadConfiguration::class => function () use ($app) {
                static::configureDisks($app);
                static::configureUnpooledPostgresConnection($app);
                static::ensureMigrationsUseUnpooledConnection($app);
            },
            HandleExceptions::class => function () use ($app) {
                static::configureCloudLogging($app);
            },
            default => fn () => true,
        })();
    }

    /**
     * Configure the GSManager Cloud disks if applicable.
     */
    public static function configureDisks(Application $app): void
    {
        if (! isset($_SERVER['GSMANAGER_CLOUD_DISK_CONFIG'])) {
            return;
        }

        $disks = json_decode($_SERVER['GSMANAGER_CLOUD_DISK_CONFIG'], true);

        foreach ($disks as $disk) {
            $app['config']->set('filesystems.disks.'.$disk['disk'], [
                'driver' => 's3',
                'key' => $disk['access_key_id'],
                'secret' => $disk['access_key_secret'],
                'bucket' => $disk['bucket'],
                'url' => $disk['url'],
                'endpoint' => $disk['endpoint'],
                'region' => 'auto',
                'use_path_style_endpoint' => false,
                'throw' => false,
                'report' => false,
            ]);

            if ($disk['is_default'] ?? false) {
                $app['config']->set('filesystems.default', $disk['disk']);
            }
        }
    }

    /**
     * Configure the unpooled GSManager Postgres connection if applicable.
     */
    public static function configureUnpooledPostgresConnection(Application $app): void
    {
        $host = $app['config']->get('database.connections.pgsql.host', '');

        if (str_contains($host, 'pg.gsmanager.cloud') &&
            str_contains($host, '-pooler')) {
            $app['config']->set(
                'database.connections.pgsql-unpooled',
                array_merge($app['config']->get('database.connections.pgsql'), [
                    'host' => str_replace('-pooler', '', $host),
                ])
            );

            $app['config']->set(
                'database.connections.pgsql.options',
                array_merge(
                    $app['config']->get('database.connections.pgsql.options', []),
                    [PDO::ATTR_EMULATE_PREPARES => true],
                ),
            );
        }
    }

    /**
     * Ensure that migrations use the unpooled Postgres connection if applicable.
     */
    public static function ensureMigrationsUseUnpooledConnection(Application $app): void
    {
        if (! is_array($app['config']->get('database.connections.pgsql-unpooled'))) {
            return;
        }

        Migrator::resolveConnectionsUsing(function ($resolver, $connection) use ($app) {
            $connection = $connection ?? $app['config']->get('database.default');

            return $resolver->connection(
                $connection === 'pgsql' ? 'pgsql-unpooled' : $connection
            );
        });
    }

    /**
     * Configure the GSManager Cloud log channels.
     */
    public static function configureCloudLogging(Application $app): void
    {
        $app['config']->set('logging.channels.stderr.formatter_with', [
            'includeStacktraces' => true,
        ]);

        $app['config']->set('logging.channels.gsmanager-cloud-socket', [
            'driver' => 'monolog',
            'handler' => SocketHandler::class,
            'formatter' => JsonFormatter::class,
            'formatter_with' => [
                'includeStacktraces' => true,
            ],
            'with' => [
                'connectionString' => $_ENV['GSMANAGER_CLOUD_LOG_SOCKET'] ??
                                      $_SERVER['GSMANAGER_CLOUD_LOG_SOCKET'] ??
                                      'unix:///tmp/cloud-init.sock',
                'persistent' => true,
            ],
        ]);
    }
}
