<?php

namespace GSManager\Contracts\Support;

interface Renderable
{
    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render();
}
