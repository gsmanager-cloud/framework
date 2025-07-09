<?php

namespace GSManager\Contracts\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
