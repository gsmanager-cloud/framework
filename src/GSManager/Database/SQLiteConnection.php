<?php

namespace GSManager\Database;

use Exception;
use GSManager\Database\Query\Grammars\SQLiteGrammar as QueryGrammar;
use GSManager\Database\Query\Processors\SQLiteProcessor;
use GSManager\Database\Schema\Grammars\SQLiteGrammar as SchemaGrammar;
use GSManager\Database\Schema\SQLiteBuilder;
use GSManager\Database\Schema\SqliteSchemaState;
use GSManager\Filesystem\Filesystem;

class SQLiteConnection extends Connection
{
    /**
     * {@inheritdoc}
     */
    public function getDriverTitle()
    {
        return 'SQLite';
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

        return "x'{$hex}'";
    }

    /**
     * Determine if the given database exception was caused by a unique constraint violation.
     *
     * @param  \Exception  $exception
     * @return bool
     */
    protected function isUniqueConstraintError(Exception $exception)
    {
        return boolval(preg_match('#(column(s)? .* (is|are) not unique|UNIQUE constraint failed: .*)#i', $exception->getMessage()));
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \GSManager\Database\Query\Grammars\SQLiteGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new QueryGrammar($this);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \GSManager\Database\Schema\SQLiteBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new SQLiteBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \GSManager\Database\Schema\Grammars\SQLiteGrammar
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
        return new SqliteSchemaState($this, $files, $processFactory);
    }

    /**
     * Get the default post processor instance.
     *
     * @return \GSManager\Database\Query\Processors\SQLiteProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new SQLiteProcessor;
    }
}
