<?php

namespace GSManager\Console\View;

enum TaskResult: int
{
    case Success = 1;
    case Failure = 2;
    case Skipped = 3;
}
