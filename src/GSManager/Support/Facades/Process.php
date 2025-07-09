<?php

namespace GSManager\Support\Facades;

use Closure;
use GSManager\Process\Factory;

/**
 * @method static \GSManager\Process\PendingProcess command(array|string $command)
 * @method static \GSManager\Process\PendingProcess path(string $path)
 * @method static \GSManager\Process\PendingProcess timeout(int $timeout)
 * @method static \GSManager\Process\PendingProcess idleTimeout(int $timeout)
 * @method static \GSManager\Process\PendingProcess forever()
 * @method static \GSManager\Process\PendingProcess env(array $environment)
 * @method static \GSManager\Process\PendingProcess input(\Traversable|resource|string|int|float|bool|null $input)
 * @method static \GSManager\Process\PendingProcess quietly()
 * @method static \GSManager\Process\PendingProcess tty(bool $tty = true)
 * @method static \GSManager\Process\PendingProcess options(array $options)
 * @method static \GSManager\Contracts\Process\ProcessResult run(array|string|null $command = null, callable|null $output = null)
 * @method static \GSManager\Process\InvokedProcess start(array|string|null $command = null, callable|null $output = null)
 * @method static bool supportsTty()
 * @method static \GSManager\Process\PendingProcess withFakeHandlers(array $fakeHandlers)
 * @method static \GSManager\Process\PendingProcess|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \GSManager\Process\PendingProcess|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \GSManager\Process\FakeProcessResult result(array|string $output = '', array|string $errorOutput = '', int $exitCode = 0)
 * @method static \GSManager\Process\FakeProcessDescription describe()
 * @method static \GSManager\Process\FakeProcessSequence sequence(array $processes = [])
 * @method static bool isRecording()
 * @method static \GSManager\Process\Factory recordIfRecording(\GSManager\Process\PendingProcess $process, \GSManager\Contracts\Process\ProcessResult $result)
 * @method static \GSManager\Process\Factory record(\GSManager\Process\PendingProcess $process, \GSManager\Contracts\Process\ProcessResult $result)
 * @method static \GSManager\Process\Factory preventStrayProcesses(bool $prevent = true)
 * @method static bool preventingStrayProcesses()
 * @method static \GSManager\Process\Factory assertRan(\Closure|string $callback)
 * @method static \GSManager\Process\Factory assertRanTimes(\Closure|string $callback, int $times = 1)
 * @method static \GSManager\Process\Factory assertNotRan(\Closure|string $callback)
 * @method static \GSManager\Process\Factory assertDidntRun(\Closure|string $callback)
 * @method static \GSManager\Process\Factory assertNothingRan()
 * @method static \GSManager\Process\Pool pool(callable $callback)
 * @method static \GSManager\Contracts\Process\ProcessResult pipe(callable|array $callback, callable|null $output = null)
 * @method static \GSManager\Process\ProcessPoolResults concurrently(callable $callback, callable|null $output = null)
 * @method static \GSManager\Process\PendingProcess newPendingProcess()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 * @method static mixed macroCall(string $method, array $parameters)
 *
 * @see \GSManager\Process\PendingProcess
 * @see \GSManager\Process\Factory
 */
class Process extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }

    /**
     * Indicate that the process factory should fake processes.
     *
     * @param  \Closure|array|null  $callback
     * @return \GSManager\Process\Factory
     */
    public static function fake(Closure|array|null $callback = null)
    {
        return tap(static::getFacadeRoot(), function ($fake) use ($callback) {
            static::swap($fake->fake($callback));
        });
    }
}
