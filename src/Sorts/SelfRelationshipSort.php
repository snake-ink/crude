<?php

namespace SnakeInk\Crude\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SelfRelationshipSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        [$relationName, $columnName] = explode('.', $property);

        $relation = $query->getRelation($relationName);
        $relatedTable = $relation->getRelated()->getTable();

        $query->select("{$relatedTable}.*")
            ->leftJoin("{$relatedTable} as self", 'self.id', '=', "{$relatedTable}.{$relation->getForeignKeyName()}")
            ->orderBy("self.{$columnName}", $descending ? 'desc' : 'asc');
    }
}
