<?php

namespace GSManager\Database;

use Closure;
use Exception;
use GSManager\Database\Query\Grammars\SqlServerGrammar as QueryGrammar;
use GSManager\Database\Query\Processors\SqlServerProcessor;
use GSManager\Database\Schema\Grammars\SqlServerGrammar as SchemaGrammar;
use GSManager\Database\Schema\SqlServerBuilder;
use GSManager\Filesystem\Filesystem;
use RuntimeException;
use Throwable;

class SqlServerConnection extends Connection
{
    /**
     * {@inheritdoc}
     */
    public function getDriverTitle()
    {
        return 'SQL Server';
    }

    /**
     * Execute a Closure within a transaction.
     *
     * @param  \Closure  $callback
     * @param  int  $attempts
     * @return mixed
     *
     * @throws \Throwable
     */
    public function transaction(Closure $callback, $attempts = 1)
    {
        for ($a = 1; $a <= $attempts; $a++) {
            if ($this->getDriverName() === 'sqlsrv') {
                return parent::transaction($callback, $attempts);
            }

            $this->getPdo()->exec('BEGIN TRAN');

            // We'll simply execute the given callback within a try / catch block
            // and if we catch any exception we can rollback the transaction
            // so that none of the changes are persisted to the database.
            try {
                $result = $callback($this);

                $this->getPdo()->exec('COMMIT TRAN');
            }

            // If we catch an exception, we will rollback so nothing gets messed
            // up in the database. Then we'll re-throw the exception so it can
            // be handled how the developer sees fit for their applications.
            catch (Throwable $e) {
                $this->getPdo()->exec('ROLLBACK TRAN');

                throw $e;
            }

            return $result;
        }
    }

    /**
     * Escape a binary value for safe SQL embedding.
     *
     * @param  string  $value
     * @return string
     */
    protected function escapeBinary($value)
    {
        $hex = bin2hex($value);

        return "0x{$hex}";
    }

    /**
     * Determine if the given database exception was caused by a unique constraint violation.
     *
     * @param  \Exception  $exception
     * @return bool
     */
    protected function isUniqueConstraintError(Exception $exception)
    {
        return boolval(preg_match('#Cannot insert duplicate key row in object#i', $exception->getMessage()));
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \GSManager\Database\Query\Grammars\SqlServerGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new QueryGrammar($this);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \GSManager\Database\Schema\SqlServerBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new SqlServerBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \GSManager\Database\Schema\Grammars\SqlServerGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return new SchemaGrammar($this);
    }

    /**
     * Get the schema state for the connection.
     *
     * @param  \GSManager\Filesystem\Filesystem|null  $files
     * @param  callable|null  $processFactory
     *
     * @throws \RuntimeException
     */
    public function getSchemaState(?Filesystem $files = null, ?callable $processFactory = null)
    {
        throw new RuntimeException('Schema dumping is not supported when using SQL Server.');
    }

    /**
     * Get the default post processor instance.
     *
     * @return \GSManager\Database\Query\Processors\SqlServerProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new SqlServerProcessor;
    }
}
