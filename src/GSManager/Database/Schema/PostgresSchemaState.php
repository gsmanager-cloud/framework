<?php

namespace GSManager\Database\Schema;

use GSManager\Database\Connection;
use GSManager\Support\Collection;

class PostgresSchemaState extends SchemaState
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
        $commands = new Collection([
            $this->baseDumpCommand().' --schema-only > '.$path,
        ]);

        if ($this->hasMigrationTable()) {
            $commands->push($this->baseDumpCommand().' -t '.$this->getMigrationTable().' --data-only >> '.$path);
        }

        $commands->map(function ($command, $path) {
            $this->makeProcess($command)->mustRun($this->output, array_merge($this->baseVariables($this->connection->getConfig()), [
                'GSMANAGER_LOAD_PATH' => $path,
            ]));
        });
    }

    /**
     * Load the given schema file into the database.
     *
     * @param  string  $path
     * @return void
     */
    public function load($path)
    {
        $command = 'pg_restore --no-owner --no-acl --clean --if-exists --host="${:GSMANAGER_LOAD_HOST}" --port="${:GSMANAGER_LOAD_PORT}" --username="${:GSMANAGER_LOAD_USER}" --dbname="${:GSMANAGER_LOAD_DATABASE}" "${:GSMANAGER_LOAD_PATH}"';

        if (str_ends_with($path, '.sql')) {
            $command = 'psql --file="${:GSMANAGER_LOAD_PATH}" --host="${:GSMANAGER_LOAD_HOST}" --port="${:GSMANAGER_LOAD_PORT}" --username="${:GSMANAGER_LOAD_USER}" --dbname="${:GSMANAGER_LOAD_DATABASE}"';
        }

        $process = $this->makeProcess($command);

        $process->mustRun(null, array_merge($this->baseVariables($this->connection->getConfig()), [
            'GSMANAGER_LOAD_PATH' => $path,
        ]));
    }

    /**
     * Get the name of the application's migration table.
     *
     * @return string
     */
    protected function getMigrationTable(): string
    {
        [$schema, $table] = $this->connection->getSchemaBuilder()->parseSchemaAndTable($this->migrationTable, withDefaultSchema: true);

        return $schema.'.'.$this->connection->getTablePrefix().$table;
    }

    /**
     * Get the base dump command arguments for PostgreSQL as a string.
     *
     * @return string
     */
    protected function baseDumpCommand()
    {
        return 'pg_dump --no-owner --no-acl --host="${:GSMANAGER_LOAD_HOST}" --port="${:GSMANAGER_LOAD_PORT}" --username="${:GSMANAGER_LOAD_USER}" --dbname="${:GSMANAGER_LOAD_DATABASE}"';
    }

    /**
     * Get the base variables for a dump / load command.
     *
     * @param  array  $config
     * @return array
     */
    protected function baseVariables(array $config)
    {
        $config['host'] ??= '';

        return [
            'GSMANAGER_LOAD_HOST' => is_array($config['host']) ? $config['host'][0] : $config['host'],
            'GSMANAGER_LOAD_PORT' => $config['port'] ?? '',
            'GSMANAGER_LOAD_USER' => $config['username'],
            'PGPASSWORD' => $config['password'],
            'GSMANAGER_LOAD_DATABASE' => $config['database'],
        ];
    }
}
