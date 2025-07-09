<?php

namespace GSManager\Foundation\Console;

use Exception;
use GSManager\Console\Command;
use GSManager\Encryption\Encrypter;
use GSManager\Filesystem\Filesystem;
use GSManager\Support\Env;
use GSManager\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

use function GSManager\Prompts\password;

#[AsCommand(name: 'env:decrypt')]
class EnvironmentDecryptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:decrypt
                    {--key= : The encryption key}
                    {--cipher= : The encryption cipher}
                    {--env= : The environment to be decrypted}
                    {--force : Overwrite the existing environment file}
                    {--path= : Path to write the decrypted file}
                    {--filename= : Filename of the decrypted file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decrypt an environment file';

    /**
     * The filesystem instance.
     *
     * @var \GSManager\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param  \GSManager\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->option('key') ?: Env::get('GSMANAGER_ENV_ENCRYPTION_KEY');

        if (! $key && $this->input->isInteractive()) {
            $key = password('What is the decryption key?');
        }

        if (! $key) {
            $this->fail('A decryption key is required.');
        }

        $cipher = $this->option('cipher') ?: 'AES-256-CBC';

        $key = $this->parseKey($key);

        $encryptedFile = ($this->option('env')
            ? Str::finish(dirname($this->gsmanager->environmentFilePath()), DIRECTORY_SEPARATOR).'.env.'.$this->option('env')
            : $this->gsmanager->environmentFilePath()).'.encrypted';

        $outputFile = $this->outputFilePath();

        if (Str::endsWith($outputFile, '.encrypted')) {
            $this->fail('Invalid filename.');
        }

        if (! $this->files->exists($encryptedFile)) {
            $this->fail('Encrypted environment file not found.');
        }

        if ($this->files->exists($outputFile) && ! $this->option('force')) {
            $this->fail('Environment file already exists.');
        }

        try {
            $encrypter = new Encrypter($key, $cipher);

            $this->files->put(
                $outputFile,
                $encrypter->decrypt($this->files->get($encryptedFile))
            );
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->components->info('Environment successfully decrypted.');

        $this->components->twoColumnDetail('Decrypted file', $outputFile);

        $this->newLine();
    }

    /**
     * Parse the encryption key.
     *
     * @param  string  $key
     * @return string
     */
    protected function parseKey(string $key)
    {
        if (Str::startsWith($key, $prefix = 'base64:')) {
            $key = base64_decode(Str::after($key, $prefix));
        }

        return $key;
    }

    /**
     * Get the output file path that should be used for the command.
     *
     * @return string
     */
    protected function outputFilePath()
    {
        $path = Str::finish($this->option('path') ?: dirname($this->gsmanager->environmentFilePath()), DIRECTORY_SEPARATOR);

        $outputFile = $this->option('filename') ?: ('.env'.($this->option('env') ? '.'.$this->option('env') : ''));
        $outputFile = ltrim($outputFile, DIRECTORY_SEPARATOR);

        return $path.$outputFile;
    }
}
