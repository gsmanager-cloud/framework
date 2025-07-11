<?php

namespace GSManager\Foundation\Console;

use GSManager\Console\Command;
use GSManager\Filesystem\Filesystem;
use GSManager\Support\Collection;
use GSManager\Support\Facades\Process;
use Symfony\Component\Console\Attribute\AsCommand;

use function GSManager\Support\gsm_binary;
use function GSManager\Support\php_binary;

#[AsCommand(name: 'install:api')]
class ApiInstallCommand extends Command
{
    use InteractsWithComposerPackages;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:api
                    {--composer=global : Absolute path to the Composer binary which should be used to install packages}
                    {--force : Overwrite any existing API routes file}
                    {--passport : Install GSManager Passport instead of GSManager Sanctum}
                    {--without-migration-prompt : Do not prompt to run pending migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an API routes file and install GSManager Sanctum or GSManager Passport';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('passport')) {
            $this->installPassport();
        } else {
            $this->installSanctum();
        }

        if (file_exists($apiRoutesPath = $this->gsmanager->basePath('routes/api.php')) &&
            ! $this->option('force')) {
            $this->components->error('API routes file already exists.');
        } else {
            $this->components->info('Published API routes file.');

            copy(__DIR__.'/stubs/api-routes.stub', $apiRoutesPath);

            if ($this->option('passport')) {
                (new Filesystem)->replaceInFile(
                    'auth:sanctum',
                    'auth:api',
                    $apiRoutesPath,
                );
            }

            $this->uncommentApiRoutesFile();
        }

        if ($this->option('passport')) {
            Process::run([
                php_binary(),
                gsm_binary(),
                'passport:install',
            ]);

            $this->components->info('API scaffolding installed. Please add the [GSManager\Passport\HasApiTokens] trait to your User model.');
        } else {
            if (! $this->option('without-migration-prompt')) {
                if ($this->confirm('One new database migration has been published. Would you like to run all pending database migrations?', true)) {
                    $this->call('migrate');
                }
            }

            $this->components->info('API scaffolding installed. Please add the [GSManager\Sanctum\HasApiTokens] trait to your User model.');
        }
    }

    /**
     * Uncomment the API routes file in the application bootstrap file.
     *
     * @return void
     */
    protected function uncommentApiRoutesFile()
    {
        $appBootstrapPath = $this->gsmanager->bootstrapPath('app.php');

        $content = file_get_contents($appBootstrapPath);

        if (str_contains($content, '// api: ')) {
            (new Filesystem)->replaceInFile(
                '// api: ',
                'api: ',
                $appBootstrapPath,
            );
        } elseif (str_contains($content, 'web: __DIR__.\'/../routes/web.php\',')) {
            (new Filesystem)->replaceInFile(
                'web: __DIR__.\'/../routes/web.php\',',
                'web: __DIR__.\'/../routes/web.php\','.PHP_EOL.'        api: __DIR__.\'/../routes/api.php\',',
                $appBootstrapPath,
            );
        } else {
            $this->components->warn("Unable to automatically add API route definition to [{$appBootstrapPath}]. API route file should be registered manually.");

            return;
        }
    }

    /**
     * Install GSManager Sanctum into the application.
     *
     * @return void
     */
    protected function installSanctum()
    {
        $this->requireComposerPackages($this->option('composer'), [
            'gsmanager/sanctum:^4.0',
        ]);

        $migrationPublished = (new Collection(scandir($this->gsmanager->databasePath('migrations'))))->contains(function ($migration) {
            return preg_match('/\d{4}_\d{2}_\d{2}_\d{6}_create_personal_access_tokens_table.php/', $migration);
        });

        if (! $migrationPublished) {
            Process::run([
                php_binary(),
                gsm_binary(),
                'vendor:publish',
                '--provider',
                'GSManager\\Sanctum\\SanctumServiceProvider',
            ]);
        }
    }

    /**
     * Install GSManager Passport into the application.
     *
     * @return void
     */
    protected function installPassport()
    {
        $this->requireComposerPackages($this->option('composer'), [
            'gsmanager/passport:^13.0',
        ]);
    }
}
