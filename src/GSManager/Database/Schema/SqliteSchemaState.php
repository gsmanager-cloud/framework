<?php

namespace GSManager\Database\Schema;

use GSManager\Database\Connection;
use GSManager\Support\Collection;

class SqliteSchemaState extends SchemaState
{
    /**
     * Dump the database's schema into a file.
     *
     * @param  \GSManager\Database\Connection  $connection
     * @param  string  $path
     * @return void
     */
    public function dump(Connection $connection, $path)
    {
        with($process = $this->makeProcess(
            $this->baseCommand().' ".schema --indent"'
        ))->setTimeout(null)->mustRun(null, array_merge($this->baseVariables($this->connection->getConfig()), [
            //
        ]));

        $migrations = preg_replace('/CREATE TABLE sqlite_.+?\);[\r\n]+/is', '', $process->getOutput());

        $this->files->put($path, $migrations.PHP_EOL);

        if ($this->hasMigrationTable()) {
            $this->appendMigrationData($path);
        }
    }

    /**
     * Append the migration data to the schema dump.
     *
     * @param  string  $path
     * @return void
     */
    protected function appendMigrationData(string $path)
    {
        with($process = $this->makeProcess(
            $this->baseCommand().' ".dump \''.$this->getMigrationTable().'\'"'
        ))->mustRun(null, array_merge($this->baseVariables($this->connection->getConfig()), [
            //
        ]));

        $migrations = (new Collection(preg_split("/\r\n|\n|\r/", $process->getOutput())))
            ->filter(fn ($line) => preg_match('/^\s*(--|INSERT\s)/iu', $line) === 1 && strlen($line) > 0)
            ->all();

        $this->files->append($path, implode(PHP_EOL, $migrations).PHP_EOL);
    }

    /**
     * Load the given schema file into the database.
     *
     * @param  string  $path
     * @return void
     */
    public function load($path)
    {
        $database = $this->connection->getDatabaseName();

        if ($database === ':memory:' ||
            str_contains($database, '?mode=memory') ||
            str_contains($database, '&mode=memory')
        ) {
            $this->connection->getPdo()->exec($this->files->get($path));

            return;
        }

        $process = $this->makeProcess($this->baseCommand().' < "${:GSMANAGER_LOAD_PATH}"');

        $process->mustRun(null, array_merge($this->baseVariables($this->connection->getConfig()), [
            'GSMANAGER_LOAD_PATH' => $path,
        ]));
    }

    /**
     * Get the base sqlite command arguments as a string.
     *
     * @return string
     */
    protected function baseCommand()
    {
        return 'sqlite3 "${:GSMANAGER_LOAD_DATABASE}"';
    }

    /**
     * Get the base variables for a dump / load command.
     *
     * @param  array  $config
     * @return array
     */
    protected function baseVariables(array $config)
    {
        return [
            'GSMANAGER_LOAD_DATABASE' => $config['database'],
        ];
    }
}
