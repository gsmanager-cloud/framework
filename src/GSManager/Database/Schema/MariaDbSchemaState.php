<?php

namespace GSManager\Database\Schema;

class MariaDbSchemaState extends MySqlSchemaState
{
    /**
     * Load the given schema file into the database.
     *
     * @param  string  $path
     * @return void
     */
    public function load($path)
    {
        $command = 'mariadb '.$this->connectionString().' --database="${:GSMANAGER_LOAD_DATABASE}" < "${:GSMANAGER_LOAD_PATH}"';

        $process = $this->makeProcess($command)->setTimeout(null);

        $process->mustRun(null, array_merge($this->baseVariables($this->connection->getConfig()), [
            'GSMANAGER_LOAD_PATH' => $path,
        ]));
    }

    /**
     * Get the base dump command arguments for MariaDB as a string.
     *
     * @return string
     */
    protected function baseDumpCommand()
    {
        $command = 'mariadb-dump '.$this->connectionString().' --no-tablespaces --skip-add-locks --skip-comments --skip-set-charset --tz-utc';

        return $command.' "${:GSMANAGER_LOAD_DATABASE}"';
    }
}
