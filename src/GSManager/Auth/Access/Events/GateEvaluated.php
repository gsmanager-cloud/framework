<?php

namespace GSManager\Auth\Access\Events;

class GateEvaluated
{
    /**
     * The authenticatable model.
     *
     * @var \GSManager\Contracts\Auth\Authenticatable|null
     */
    public $user;

    /**
     * The ability being evaluated.
     *
     * @var string
     */
    public $ability;

    /**
     * The result of the evaluation.
     *
     * @var bool|null
     */
    public $result;

    /**
     * The arguments given during evaluation.
     *
     * @var array
     */
    public $arguments;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Contracts\Auth\Authenticatable|null  $user
     * @param  string  $ability
     * @param  bool|null  $result
     * @param  array  $arguments
     */
    public function __construct($user, $ability, $result, $arguments)
    {
        $this->user = $user;
        $this->ability = $ability;
        $this->result = $result;
        $this->arguments = $arguments;
    }
}
