<?php

namespace GSManager\Contracts\Support;

interface Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \GSManager\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request);
}
