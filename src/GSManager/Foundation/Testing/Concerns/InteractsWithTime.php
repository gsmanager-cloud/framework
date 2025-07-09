<?php

namespace GSManager\Foundation\Testing\Concerns;

use GSManager\Foundation\Testing\Wormhole;
use GSManager\Support\Carbon;

trait InteractsWithTime
{
    /**
     * Freeze time.
     *
     * @param  callable|null  $callback
     * @return mixed
     */
    public function freezeTime($callback = null)
    {
        $result = $this->travelTo($now = Carbon::now(), $callback);

        return is_null($callback) ? $now : $result;
    }

    /**
     * Freeze time at the beginning of the current second.
     *
     * @param  callable|null  $callback
     * @return mixed
     */
    public function freezeSecond($callback = null)
    {
        $result = $this->travelTo($now = Carbon::now()->startOfSecond(), $callback);

        return is_null($callback) ? $now : $result;
    }

    /**
     * Begin travelling to another time.
     *
     * @param  int  $value
     * @return \GSManager\Foundation\Testing\Wormhole
     */
    public function travel($value)
    {
        return new Wormhole($value);
    }

    /**
     * Travel to another time.
     *
     * @param  \DateTimeInterface|\Closure|\GSManager\Support\Carbon|string|bool|null  $date
     * @param  callable|null  $callback
     * @return mixed
     */
    public function travelTo($date, $callback = null)
    {
        Carbon::setTestNow($date);

        if ($callback) {
            return tap($callback($date), function () {
                Carbon::setTestNow();
            });
        }
    }

    /**
     * Travel back to the current time.
     *
     * @return \DateTimeInterface
     */
    public function travelBack()
    {
        return Wormhole::back();
    }
}
