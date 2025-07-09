<?php

namespace GSManager\Database\Eloquent\Relations;

use GSManager\Database\Eloquent\Collection as EloquentCollection;

/**
 * @template TRelatedModel of \GSManager\Database\Eloquent\Model
 * @template TDeclaringModel of \GSManager\Database\Eloquent\Model
 *
 * @extends \GSManager\Database\Eloquent\Relations\MorphOneOrMany<TRelatedModel, TDeclaringModel, \GSManager\Database\Eloquent\Collection<int, TRelatedModel>>
 */
class MorphMany extends MorphOneOrMany
{
    /**
     * Convert the relationship to a "morph one" relationship.
     *
     * @return \GSManager\Database\Eloquent\Relations\MorphOne<TRelatedModel, TDeclaringModel>
     */
    public function one()
    {
        return MorphOne::noConstraints(fn () => tap(
            new MorphOne(
                $this->getQuery(),
                $this->getParent(),
                $this->morphType,
                $this->foreignKey,
                $this->localKey
            ),
            function ($morphOne) {
                if ($inverse = $this->getInverseRelationship()) {
                    $morphOne->inverse($inverse);
                }
            }
        ));
    }

    /** @inheritDoc */
    public function getResults()
    {
        return ! is_null($this->getParentKey())
            ? $this->query->get()
            : $this->related->newCollection();
    }

    /** @inheritDoc */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }

        return $models;
    }

    /** @inheritDoc */
    public function match(array $models, EloquentCollection $results, $relation)
    {
        return $this->matchMany($models, $results, $relation);
    }

    /** @inheritDoc */
    public function forceCreate(array $attributes = [])
    {
        $attributes[$this->getMorphType()] = $this->morphClass;

        return parent::forceCreate($attributes);
    }
}
