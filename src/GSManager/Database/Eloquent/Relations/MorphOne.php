<?php

namespace GSManager\Database\Eloquent\Relations;

use GSManager\Contracts\Database\Eloquent\SupportsPartialRelations;
use GSManager\Database\Eloquent\Builder;
use GSManager\Database\Eloquent\Collection as EloquentCollection;
use GSManager\Database\Eloquent\Model;
use GSManager\Database\Eloquent\Relations\Concerns\CanBeOneOfMany;
use GSManager\Database\Eloquent\Relations\Concerns\ComparesRelatedModels;
use GSManager\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use GSManager\Database\Query\JoinClause;

/**
 * @template TRelatedModel of \GSManager\Database\Eloquent\Model
 * @template TDeclaringModel of \GSManager\Database\Eloquent\Model
 *
 * @extends \GSManager\Database\Eloquent\Relations\MorphOneOrMany<TRelatedModel, TDeclaringModel, ?TRelatedModel>
 */
class MorphOne extends MorphOneOrMany implements SupportsPartialRelations
{
    use CanBeOneOfMany, ComparesRelatedModels, SupportsDefaultModels;

    /** @inheritDoc */
    public function getResults()
    {
        if (is_null($this->getParentKey())) {
            return $this->getDefaultFor($this->parent);
        }

        return $this->query->first() ?: $this->getDefaultFor($this->parent);
    }

    /** @inheritDoc */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->getDefaultFor($model));
        }

        return $models;
    }

    /** @inheritDoc */
    public function match(array $models, EloquentCollection $results, $relation)
    {
        return $this->matchOne($models, $results, $relation);
    }

    /** @inheritDoc */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
    {
        if ($this->isOneOfMany()) {
            $this->mergeOneOfManyJoinsTo($query);
        }

        return parent::getRelationExistenceQuery($query, $parentQuery, $columns);
    }

    /**
     * Add constraints for inner join subselect for one of many relationships.
     *
     * @param  \GSManager\Database\Eloquent\Builder<TRelatedModel>  $query
     * @param  string|null  $column
     * @param  string|null  $aggregate
     * @return void
     */
    public function addOneOfManySubQueryConstraints(Builder $query, $column = null, $aggregate = null)
    {
        $query->addSelect($this->foreignKey, $this->morphType);
    }

    /**
     * Get the columns that should be selected by the one of many subquery.
     *
     * @return array|string
     */
    public function getOneOfManySubQuerySelectColumns()
    {
        return [$this->foreignKey, $this->morphType];
    }

    /**
     * Add join query constraints for one of many relationships.
     *
     * @param  \GSManager\Database\Query\JoinClause  $join
     * @return void
     */
    public function addOneOfManyJoinSubQueryConstraints(JoinClause $join)
    {
        $join
            ->on($this->qualifySubSelectColumn($this->morphType), '=', $this->qualifyRelatedColumn($this->morphType))
            ->on($this->qualifySubSelectColumn($this->foreignKey), '=', $this->qualifyRelatedColumn($this->foreignKey));
    }

    /**
     * Make a new related instance for the given model.
     *
     * @param  TDeclaringModel  $parent
     * @return TRelatedModel
     */
    public function newRelatedInstanceFor(Model $parent)
    {
        return tap($this->related->newInstance(), function ($instance) use ($parent) {
            $instance->setAttribute($this->getForeignKeyName(), $parent->{$this->localKey})
                ->setAttribute($this->getMorphType(), $this->morphClass);

            $this->applyInverseRelationToModel($instance, $parent);
        });
    }

    /**
     * Get the value of the model's foreign key.
     *
     * @param  TRelatedModel  $model
     * @return int|string
     */
    protected function getRelatedKeyFrom(Model $model)
    {
        return $model->getAttribute($this->getForeignKeyName());
    }
}
