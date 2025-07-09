<?php

namespace GSManager\Contracts\Validation;

use GSManager\Validation\Validator;

interface ValidatorAwareRule
{
    /**
     * Set the current validator.
     *
     * @param  \GSManager\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator(Validator $validator);
}
