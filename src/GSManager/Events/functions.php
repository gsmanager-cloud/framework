<?php

namespace GSManager\Events;

use Closure;

if (! function_exists('GSManager\Events\queueable')) {
    /**
     * Create a new queued Closure event listener.
     *
     * @param  \Closure  $closure
     * @return \GSManager\Events\QueuedClosure
     */
    function queueable(Closure $closure)
    {
        return new QueuedClosure($closure);
    }
}
