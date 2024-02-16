<?php

namespace SnakeInk\Crude\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class OneLevelRelationshipSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        [$relationName, $columnName] = explode('.', $property);

        $relation = $query->getRelation($relationName);

        $subquery = $relation
            ->getQuery()
            ->select($columnName)
            ->whereColumn($relation->getQualifiedForeignKeyName(), $relation->getQualifiedOwnerKeyName());

        $query->orderBy($subquery, $descending ? 'desc' : 'asc');
    }
}
