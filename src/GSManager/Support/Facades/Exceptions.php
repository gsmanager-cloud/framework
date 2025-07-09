<?php

namespace GSManager\Support\Facades;

use GSManager\Contracts\Debug\ExceptionHandler;
use GSManager\Support\Arr;
use GSManager\Support\Testing\Fakes\ExceptionHandlerFake;

/**
 * @method static void register()
 * @method static \GSManager\Foundation\Exceptions\ReportableHandler reportable(callable $reportUsing)
 * @method static \GSManager\Foundation\Exceptions\Handler renderable(callable $renderUsing)
 * @method static \GSManager\Foundation\Exceptions\Handler map(\Closure|string $from, \Closure|string|null $to = null)
 * @method static \GSManager\Foundation\Exceptions\Handler dontReport(array|string $exceptions)
 * @method static \GSManager\Foundation\Exceptions\Handler ignore(array|string $exceptions)
 * @method static \GSManager\Foundation\Exceptions\Handler dontFlash(array|string $attributes)
 * @method static \GSManager\Foundation\Exceptions\Handler level(string $type, string $level)
 * @method static void report(\Throwable $e)
 * @method static bool shouldReport(\Throwable $e)
 * @method static \GSManager\Foundation\Exceptions\Handler throttleUsing(callable $throttleUsing)
 * @method static \GSManager\Foundation\Exceptions\Handler stopIgnoring(array|string $exceptions)
 * @method static \GSManager\Foundation\Exceptions\Handler buildContextUsing(\Closure $contextCallback)
 * @method static \Symfony\Component\HttpFoundation\Response render(\GSManager\Http\Request $request, \Throwable $e)
 * @method static \GSManager\Foundation\Exceptions\Handler respondUsing(callable $callback)
 * @method static \GSManager\Foundation\Exceptions\Handler shouldRenderJsonWhen(callable $callback)
 * @method static \GSManager\Foundation\Exceptions\Handler dontReportDuplicates()
 * @method static \GSManager\Contracts\Debug\ExceptionHandler handler()
 * @method static void assertReported(\Closure|string $exception)
 * @method static void assertReportedCount(int $count)
 * @method static void assertNotReported(\Closure|string $exception)
 * @method static void assertNothingReported()
 * @method static void renderForConsole(\Symfony\Component\Console\Output\OutputInterface $output, \Throwable $e)
 * @method static \GSManager\Support\Testing\Fakes\ExceptionHandlerFake throwOnReport()
 * @method static \GSManager\Support\Testing\Fakes\ExceptionHandlerFake throwFirstReported()
 * @method static array reported()
 * @method static \GSManager\Support\Testing\Fakes\ExceptionHandlerFake setHandler(\GSManager\Contracts\Debug\ExceptionHandler $handler)
 *
 * @see \GSManager\Foundation\Exceptions\Handler
 * @see \GSManager\Support\Testing\Fakes\ExceptionHandlerFake
 */
class Exceptions extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @param  array<int, class-string<\Throwable>>|class-string<\Throwable>  $exceptions
     * @return \GSManager\Support\Testing\Fakes\ExceptionHandlerFake
     */
    public static function fake(array|string $exceptions = [])
    {
        $exceptionHandler = static::isFake()
            ? static::getFacadeRoot()->handler()
            : static::getFacadeRoot();

        return tap(new ExceptionHandlerFake($exceptionHandler, Arr::wrap($exceptions)), function ($fake) {
            static::swap($fake);
        });
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ExceptionHandler::class;
    }
}
