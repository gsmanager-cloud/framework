<?php

namespace GSManager\Console\View\Components;

use Symfony\Component\Console\Output\OutputInterface;

class Success extends Component
{
    /**
     * Renders the component using the given arguments.
     *
     * @param  string  $string
     * @param  int  $verbosity
     * @return void
     */
    public function render($string, $verbosity = OutputInterface::VERBOSITY_NORMAL)
    {
        with(new Line($this->output))->render('success', $string, $verbosity);
    }
}
