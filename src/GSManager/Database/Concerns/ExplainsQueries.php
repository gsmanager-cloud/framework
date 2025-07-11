<?php

namespace GSManager\Database\Concerns;

use GSManager\Support\Collection;

trait ExplainsQueries
{
    /**
     * Explains the query.
     *
     * @return \GSManager\Support\Collection
     */
    public function explain()
    {
        $sql = $this->toSql();

        $bindings = $this->getBindings();

        $explanation = $this->getConnection()->select('EXPLAIN '.$sql, $bindings);

        return new Collection($explanation);
    }
}
