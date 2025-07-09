<?php

namespace GSManager\Bus\Events;

use GSManager\Bus\Batch;

class BatchDispatched
{
    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Bus\Batch  $batch  The batch instance.
     */
    public function __construct(
        public Batch $batch,
    ) {
    }
}
