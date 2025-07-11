<?php

namespace GSManager\Support\Facades;

/**
 * @method static array preloadedAssets()
 * @method static string|null cspNonce()
 * @method static string useCspNonce(string|null $nonce = null)
 * @method static \GSManager\Foundation\Vite useIntegrityKey(string|false $key)
 * @method static \GSManager\Foundation\Vite withEntryPoints(array $entryPoints)
 * @method static \GSManager\Foundation\Vite mergeEntryPoints(array $entryPoints)
 * @method static \GSManager\Foundation\Vite useManifestFilename(string $filename)
 * @method static \GSManager\Foundation\Vite createAssetPathsUsing(callable|null $resolver)
 * @method static string hotFile()
 * @method static \GSManager\Foundation\Vite useHotFile(string $path)
 * @method static \GSManager\Foundation\Vite useBuildDirectory(string $path)
 * @method static \GSManager\Foundation\Vite useScriptTagAttributes(callable|array $attributes)
 * @method static \GSManager\Foundation\Vite useStyleTagAttributes(callable|array $attributes)
 * @method static \GSManager\Foundation\Vite usePreloadTagAttributes(callable|array|false $attributes)
 * @method static \GSManager\Foundation\Vite prefetch(int|null $concurrency = null, string $event = 'load')
 * @method static \GSManager\Foundation\Vite useWaterfallPrefetching(int|null $concurrency = null)
 * @method static \GSManager\Foundation\Vite useAggressivePrefetching()
 * @method static \GSManager\Foundation\Vite usePrefetchStrategy(string|null $strategy, array $config = [])
 * @method static \GSManager\Support\HtmlString|void reactRefresh()
 * @method static string asset(string $asset, string|null $buildDirectory = null)
 * @method static string content(string $asset, string|null $buildDirectory = null)
 * @method static string|null manifestHash(string|null $buildDirectory = null)
 * @method static bool isRunningHot()
 * @method static string toHtml()
 * @method static void flush()
 * @method static void macro(string $name, object|callable $macro)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \GSManager\Foundation\Vite
 */
class Vite extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \GSManager\Foundation\Vite::class;
    }
}
