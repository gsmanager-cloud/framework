<?php

namespace GSManager\Log;

if (! function_exists('GSManager\Log\log')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string|null  $message
     * @param  array  $context
     * @return ($message is null ? \GSManager\Log\LogManager : null)
     */
    function log($message = null, array $context = [])
    {
        return logger($message, $context);
    }
}
