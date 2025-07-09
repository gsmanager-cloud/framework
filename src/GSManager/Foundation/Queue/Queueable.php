<?php

namespace GSManager\Foundation\Queue;

use GSManager\Bus\Queueable as QueueableByBus;
use GSManager\Foundation\Bus\Dispatchable;
use GSManager\Queue\InteractsWithQueue;
use GSManager\Queue\SerializesModels;

trait Queueable
{
    use Dispatchable, InteractsWithQueue, QueueableByBus, SerializesModels;
}
