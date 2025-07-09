<?php

namespace GSManager\Support\Facades;

use GSManager\Console\Scheduling\Schedule as ConsoleSchedule;

/**
 * @method static \GSManager\Console\Scheduling\CallbackEvent call(string|callable $callback, array $parameters = [])
 * @method static \GSManager\Console\Scheduling\Event command(string $command, array $parameters = [])
 * @method static \GSManager\Console\Scheduling\CallbackEvent job(object|string $job, string|null $queue = null, string|null $connection = null)
 * @method static \GSManager\Console\Scheduling\Event exec(string $command, array $parameters = [])
 * @method static void group(\Closure $events)
 * @method static string compileArrayInput(string|int $key, array $value)
 * @method static bool serverShouldRun(\GSManager\Console\Scheduling\Event $event, \DateTimeInterface $time)
 * @method static \GSManager\Support\Collection dueEvents(\GSManager\Contracts\Foundation\Application $app)
 * @method static \GSManager\Console\Scheduling\Event[] events()
 * @method static \GSManager\Console\Scheduling\Schedule useCache(string $store)
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes withoutOverlapping(int $expiresAt = 1440)
 * @method static void mergeAttributes(\GSManager\Console\Scheduling\Event $event)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes user(string $user)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes environments(array|mixed $environments)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes evenInMaintenanceMode()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes onOneServer()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes runInBackground()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes when(\Closure|bool $callback)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes skip(\Closure|bool $callback)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes name(string $description)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes description(string $description)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes cron(string $expression)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes between(string $startTime, string $endTime)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes unlessBetween(string $startTime, string $endTime)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everySecond()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyTwoSeconds()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyFiveSeconds()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyTenSeconds()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyFifteenSeconds()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyTwentySeconds()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyThirtySeconds()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyMinute()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyTwoMinutes()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyThreeMinutes()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyFourMinutes()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyFiveMinutes()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyTenMinutes()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyFifteenMinutes()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyThirtyMinutes()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes hourly()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes hourlyAt(array|string|int|int[] $offset)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyOddHour(array|string|int $offset = 0)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyTwoHours(array|string|int $offset = 0)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyThreeHours(array|string|int $offset = 0)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everyFourHours(array|string|int $offset = 0)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes everySixHours(array|string|int $offset = 0)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes daily()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes at(string $time)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes dailyAt(string $time)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes twiceDaily(int $first = 1, int $second = 13)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes twiceDailyAt(int $first = 1, int $second = 13, int $offset = 0)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes weekdays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes weekends()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes mondays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes tuesdays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes wednesdays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes thursdays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes fridays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes saturdays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes sundays()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes weekly()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes weeklyOn(array|mixed $dayOfWeek, string $time = '0:0')
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes monthly()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes monthlyOn(int $dayOfMonth = 1, string $time = '0:0')
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes twiceMonthly(int $first = 1, int $second = 16, string $time = '0:0')
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes lastDayOfMonth(string $time = '0:0')
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes quarterly()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes quarterlyOn(int $dayOfQuarter = 1, string $time = '0:0')
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes yearly()
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes yearlyOn(int $month = 1, int|string $dayOfMonth = 1, string $time = '0:0')
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes days(array|mixed $days)
 * @method static \GSManager\Console\Scheduling\PendingEventAttributes timezone(\DateTimeZone|string $timezone)
 *
 * @see \GSManager\Console\Scheduling\Schedule
 */
class Schedule extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ConsoleSchedule::class;
    }
}
