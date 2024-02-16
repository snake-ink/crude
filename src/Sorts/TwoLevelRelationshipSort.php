<?php

namespace SnakeInk\Crude\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class TwoLevelRelationshipSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        [$relationName, $secondRelationName, $columnName] = explode('.', $property);

        $queryTable = $query->getModel()->getTable();

        $relation = $query->getRelation($relationName);
        $relatedTable = $relation->getRelated()->getTable();

        $secondRelation = $relation->getRelation($secondRelationName);
        $secondRelatedTable = $secondRelation->getRelated()->getTable();

        $query->select("{$queryTable}.*")
            ->leftJoin("{$relatedTable} as first", 'first.id', '=', "{$queryTable}.{$relation->getForeignKeyName()}")
            ->leftJoin("{$secondRelatedTable} as second", 'second.id', '=', "first.{$secondRelation->getForeignKeyName()}")
            ->orderBy("second.{$columnName}", $descending ? 'desc' : 'asc');
    }
}
