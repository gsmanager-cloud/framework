<?php

namespace GSManager\Foundation\Exceptions\Renderer;

use GSManager\Contracts\View\Factory;
use GSManager\Foundation\Exceptions\Renderer\Mappers\BladeMapper;
use GSManager\Http\Request;
use GSManager\Support\Collection;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Throwable;

class Renderer
{
    /**
     * The path to the renderer's distribution files.
     *
     * @var string
     */
    protected const DIST = __DIR__.'/../../resources/exceptions/renderer/dist/';

    /**
     * The view factory instance.
     *
     * @var \GSManager\Contracts\View\Factory
     */
    protected $viewFactory;

    /**
     * The exception listener instance.
     *
     * @var \GSManager\Foundation\Exceptions\Renderer\Listener
     */
    protected $listener;

    /**
     * The HTML error renderer instance.
     *
     * @var \Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer
     */
    protected $htmlErrorRenderer;

    /**
     * The Blade mapper instance.
     *
     * @var \GSManager\Foundation\Exceptions\Renderer\Mappers\BladeMapper
     */
    protected $bladeMapper;

    /**
     * The application's base path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Creates a new exception renderer instance.
     *
     * @param  \GSManager\Contracts\View\Factory  $viewFactory
     * @param  \GSManager\Foundation\Exceptions\Renderer\Listener  $listener
     * @param  \Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer  $htmlErrorRenderer
     * @param  \GSManager\Foundation\Exceptions\Renderer\Mappers\BladeMapper  $bladeMapper
     * @param  string  $basePath
     */
    public function __construct(
        Factory $viewFactory,
        Listener $listener,
        HtmlErrorRenderer $htmlErrorRenderer,
        BladeMapper $bladeMapper,
        string $basePath,
    ) {
        $this->viewFactory = $viewFactory;
        $this->listener = $listener;
        $this->htmlErrorRenderer = $htmlErrorRenderer;
        $this->bladeMapper = $bladeMapper;
        $this->basePath = $basePath;
    }

    /**
     * Render the given exception as an HTML string.
     *
     * @param  \GSManager\Http\Request  $request
     * @param  \Throwable  $throwable
     * @return string
     */
    public function render(Request $request, Throwable $throwable)
    {
        $flattenException = $this->bladeMapper->map(
            $this->htmlErrorRenderer->render($throwable),
        );

        return $this->viewFactory->make('gsmanager-exceptions-renderer::show', [
            'exception' => new Exception($flattenException, $request, $this->listener, $this->basePath),
        ])->render();
    }

    /**
     * Get the renderer's CSS content.
     *
     * @return string
     */
    public static function css()
    {
        return (new Collection([
            ['styles.css', []],
            ['light-mode.css', ['data-theme' => 'light']],
            ['dark-mode.css', ['data-theme' => 'dark']],
        ]))->map(function ($fileAndAttributes) {
            [$filename, $attributes] = $fileAndAttributes;

            return '<style '.(new Collection($attributes))->map(function ($value, $attribute) {
                return $attribute.'="'.$value.'"';
            })->implode(' ').'>'
                .file_get_contents(static::DIST.$filename)
                .'</style>';
        })->implode('');
    }

    /**
     * Get the renderer's JavaScript content.
     *
     * @return string
     */
    public static function js()
    {
        $viteJsAutoRefresh = '';

        $vite = app(\GSManager\Foundation\Vite::class);

        if (is_file($vite->hotFile())) {
            $viteJsAutoRefresh = $vite->__invoke([]);
        }

        return '<script>'
            .file_get_contents(static::DIST.'scripts.js')
            .'</script>'.$viteJsAutoRefresh;
    }
}
