<?php

namespace GSManager\Database\Eloquent;

interface Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @template TModel of \GSManager\Database\Eloquent\Model
     *
     * @param  \GSManager\Database\Eloquent\Builder<TModel>  $builder
     * @param  TModel  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model);
}
