<?php

namespace GSManager\Support\Facades;

use GSManager\Bus\BatchRepository;
use GSManager\Contracts\Bus\Dispatcher as BusDispatcherContract;
use GSManager\Foundation\Bus\PendingChain;
use GSManager\Support\Testing\Fakes\BusFake;

/**
 * @method static mixed dispatch(mixed $command)
 * @method static mixed dispatchSync(mixed $command, mixed $handler = null)
 * @method static mixed dispatchNow(mixed $command, mixed $handler = null)
 * @method static \GSManager\Bus\Batch|null findBatch(string $batchId)
 * @method static \GSManager\Bus\PendingBatch batch(\GSManager\Support\Collection|array|mixed $jobs)
 * @method static \GSManager\Foundation\Bus\PendingChain chain(\GSManager\Support\Collection|array $jobs)
 * @method static bool hasCommandHandler(mixed $command)
 * @method static bool|mixed getCommandHandler(mixed $command)
 * @method static mixed dispatchToQueue(mixed $command)
 * @method static void dispatchAfterResponse(mixed $command, mixed $handler = null)
 * @method static \GSManager\Bus\Dispatcher pipeThrough(array $pipes)
 * @method static \GSManager\Bus\Dispatcher map(array $map)
 * @method static \GSManager\Bus\Dispatcher withDispatchingAfterResponses()
 * @method static \GSManager\Bus\Dispatcher withoutDispatchingAfterResponses()
 * @method static \GSManager\Support\Testing\Fakes\BusFake except(array|string $jobsToDispatch)
 * @method static void assertDispatched(string|\Closure $command, callable|int|null $callback = null)
 * @method static void assertDispatchedTimes(string|\Closure $command, int $times = 1)
 * @method static void assertNotDispatched(string|\Closure $command, callable|null $callback = null)
 * @method static void assertNothingDispatched()
 * @method static void assertDispatchedSync(string|\Closure $command, callable|int|null $callback = null)
 * @method static void assertDispatchedSyncTimes(string|\Closure $command, int $times = 1)
 * @method static void assertNotDispatchedSync(string|\Closure $command, callable|null $callback = null)
 * @method static void assertDispatchedAfterResponse(string|\Closure $command, callable|int|null $callback = null)
 * @method static void assertDispatchedAfterResponseTimes(string|\Closure $command, int $times = 1)
 * @method static void assertNotDispatchedAfterResponse(string|\Closure $command, callable|null $callback = null)
 * @method static void assertChained(array $expectedChain)
 * @method static void assertNothingChained()
 * @method static void assertDispatchedWithoutChain(string|\Closure $command, callable|null $callback = null)
 * @method static \GSManager\Support\Testing\Fakes\ChainedBatchTruthTest chainedBatch(\Closure $callback)
 * @method static void assertBatched(callable $callback)
 * @method static void assertBatchCount(int $count)
 * @method static void assertNothingBatched()
 * @method static void assertNothingPlaced()
 * @method static \GSManager\Support\Collection dispatched(string $command, callable|null $callback = null)
 * @method static \GSManager\Support\Collection dispatchedSync(string $command, callable|null $callback = null)
 * @method static \GSManager\Support\Collection dispatchedAfterResponse(string $command, callable|null $callback = null)
 * @method static \GSManager\Support\Collection batched(callable $callback)
 * @method static bool hasDispatched(string $command)
 * @method static bool hasDispatchedSync(string $command)
 * @method static bool hasDispatchedAfterResponse(string $command)
 * @method static \GSManager\Bus\Batch dispatchFakeBatch(string $name = '')
 * @method static \GSManager\Bus\Batch recordPendingBatch(\GSManager\Bus\PendingBatch $pendingBatch)
 * @method static \GSManager\Support\Testing\Fakes\BusFake serializeAndRestore(bool $serializeAndRestore = true)
 * @method static array dispatchedBatches()
 *
 * @see \GSManager\Bus\Dispatcher
 * @see \GSManager\Support\Testing\Fakes\BusFake
 */
class Bus extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @param  array|string  $jobsToFake
     * @param  \GSManager\Bus\BatchRepository|null  $batchRepository
     * @return \GSManager\Support\Testing\Fakes\BusFake
     */
    public static function fake($jobsToFake = [], ?BatchRepository $batchRepository = null)
    {
        $actualDispatcher = static::isFake()
            ? static::getFacadeRoot()->dispatcher
            : static::getFacadeRoot();

        return tap(new BusFake($actualDispatcher, $jobsToFake, $batchRepository), function ($fake) {
            static::swap($fake);
        });
    }

    /**
     * Dispatch the given chain of jobs.
     *
     * @param  array|mixed  $jobs
     * @return \GSManager\Foundation\Bus\PendingDispatch
     */
    public static function dispatchChain($jobs)
    {
        $jobs = is_array($jobs) ? $jobs : func_get_args();

        return (new PendingChain(array_shift($jobs), $jobs))
            ->dispatch();
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BusDispatcherContract::class;
    }
}
