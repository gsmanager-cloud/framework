<?php

namespace GSManager\Foundation\Testing\Concerns;

use GSManager\Support\Facades\View as ViewFacade;
use GSManager\Support\MessageBag;
use GSManager\Support\ViewErrorBag;
use GSManager\Testing\TestComponent;
use GSManager\Testing\TestView;
use GSManager\View\View;

trait InteractsWithViews
{
    /**
     * Create a new TestView from the given view.
     *
     * @param  string  $view
     * @param  \GSManager\Contracts\Support\Arrayable|array  $data
     * @return \GSManager\Testing\TestView
     */
    protected function view(string $view, $data = [])
    {
        return new TestView(view($view, $data));
    }

    /**
     * Render the contents of the given Blade template string.
     *
     * @param  string  $template
     * @param  \GSManager\Contracts\Support\Arrayable|array  $data
     * @return \GSManager\Testing\TestView
     */
    protected function blade(string $template, $data = [])
    {
        $tempDirectory = sys_get_temp_dir();

        if (! in_array($tempDirectory, ViewFacade::getFinder()->getPaths())) {
            ViewFacade::addLocation(sys_get_temp_dir());
        }

        $tempFileInfo = pathinfo(tempnam($tempDirectory, 'gsmanager-blade'));

        $tempFile = $tempFileInfo['dirname'].'/'.$tempFileInfo['filename'].'.blade.php';

        file_put_contents($tempFile, $template);

        return new TestView(view($tempFileInfo['filename'], $data));
    }

    /**
     * Render the given view component.
     *
     * @param  string  $componentClass
     * @param  \GSManager\Contracts\Support\Arrayable|array  $data
     * @return \GSManager\Testing\TestComponent
     */
    protected function component(string $componentClass, $data = [])
    {
        $component = $this->app->make($componentClass, $data);

        $view = value($component->resolveView(), $data);

        $view = $view instanceof View
            ? $view->with($component->data())
            : view($view, $component->data());

        return new TestComponent($component, $view);
    }

    /**
     * Populate the shared view error bag with the given errors.
     *
     * @param  array  $errors
     * @param  string  $key
     * @return $this
     */
    protected function withViewErrors(array $errors, $key = 'default')
    {
        ViewFacade::share('errors', (new ViewErrorBag)->put($key, new MessageBag($errors)));

        return $this;
    }
}
