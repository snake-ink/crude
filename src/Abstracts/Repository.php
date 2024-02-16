<?php

namespace SnakeInk\Crude\Abstracts;

use Closure;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\Relation;
use SnakeInk\Crude\Concerns\ChecksIfClassImplementsAnother;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class Repository
{
    use ChecksIfClassImplementsAnother;

    private $queryBuilderModelClass;

    public function __construct(
        protected string $modelClass = Model::class,
        private Builder|QueryBuilder|null $queryBuilder = null
    ) {
        $this->modelClass = $modelClass;
        $this->queryBuilderModelClass = $modelClass;
        $this->queryBuilder = $queryBuilder;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function getQueryBuilder(): Builder|QueryBuilder|null
    {
        return $this->queryBuilder;
    }

    public function getQueryBuilderResults(): Collection
    {
        return $this->queryBuilder->get();
    }

    public function all(
        bool $useQueryBuilder = false,
        ?string $queryBuilderModelClass = null,
        ?int $perPage = null,
        ?array $paginationArguments = null,
        ?object $starterQuery = null
    ): Collection|LengthAwarePaginator|null {
        if ($useQueryBuilder) {
            $query = $this->startQueryBuilder(query: $starterQuery, for: $queryBuilderModelClass)
                ->buildQueryWithSelects()
                ->buildQueryWithIncludes()
                ->buildQueryWithFilters()
                ->buildQueryWithSorts()
                ->getQueryBuilder();
        } else {
            $query = $starterQuery ?? $this->modelClass::query();
        }

        return !empty($paginationArguments) ?
            $query?->paginate(...$paginationArguments) :
            (empty($perPage) ?
                $query?->get() :
                $query?->paginate($perPage));
    }

    public function allWhere(
        array $whereArguments,
        bool $useQueryBuilder = false,
        ?string $queryBuilderModelClass = null,
        ?int $perPage = null,
        ?array $paginationArguments = null,
    ): Collection|LengthAwarePaginator|null {
        $query = $this->where(...$whereArguments);

        return $this->all(
            useQueryBuilder: $useQueryBuilder,
            queryBuilderModelClass: $queryBuilderModelClass,
            perPage: $perPage,
            paginationArguments: $paginationArguments,
            starterQuery: $query
        );
    }

    public function allRelatedEntities(
        Model|int|string $entity,
        string $relationName,
        bool $failIfNotFound = false,
        bool $useQueryBuilder = false,
        ?string $queryBuilderModelClass = null,
        ?int $perPage = null,
        ?array $paginationArguments = null,
    ): Collection|LengthAwarePaginator|null {
        $entity = $this->resolveEntity(
            entity: $entity,
            failIfNotFound: $failIfNotFound
        );

        $this->verifyRelation(methodName: $relationName);

        if ($useQueryBuilder && !empty($entity)) {
            $query = $this->startQueryBuilder(query: $entity->$relationName(), for: $queryBuilderModelClass)
                ->buildQueryWithSelects()
                ->buildQueryWithIncludes()
                ->buildQueryWithFilters()
                ->buildQueryWithSorts()
                ->getQueryBuilder();
        } else {
            $query = $entity?->$relationName();
        }

        return !empty($paginationArguments) ?
            $query?->paginate(...$paginationArguments) :
            (empty($perPage) ?
                $query?->get() :
                $query?->paginate($perPage));
    }

    public function first(
        callable $truthTestCallback = null,
        $default = null
    ): ?object {
        return $this->all()->first($truthTestCallback, $default);
    }

    public function firstOrNew(
        array $attributes = [],
        array $values = []
    ): Model {
        return $this->modelClass::firstOrNew($attributes, $values);
    }

    public function firstOrCreate(
        array $attributes = [],
        array $values = []
    ): Model {
        return $this->modelClass::firstOrCreate($attributes, $values);
    }

    public function find(
        int|string $id,
        bool $failIfNotFound = false,
        array $columns = ['*'],
        bool $useQueryBuilder = false,
        ?object $starterQuery = null
    ): ?Model {
        if ($useQueryBuilder) {
            $queryBuilder = $this->startQueryBuilder($starterQuery)
                ->buildQueryWithSelects()
                ->buildQueryWithIncludes()
                ->getQueryBuilder();

            return $failIfNotFound ?
                $queryBuilder?->findOrFail($id) :
                $queryBuilder?->find($id);
        }

        $query = $starterQuery ?? $this->modelClass::query();

        return $failIfNotFound ?
            $query->findOrFail($id, $columns) :
            $query->find($id, $columns);
    }

    public function resolveEntity(
        Model|int|string|null $entity,
        bool $failIfNotFound = false,
        ?object $starterQuery = null
    ): ?Model {
        if (empty($entity)) {
            return null;
        }

        if (self::isId($entity)) {
            $entity = $this->find(
                id: $entity,
                failIfNotFound: $failIfNotFound,
                starterQuery: $starterQuery
            );
        } elseif (!empty($starterQuery)) {
            $entity = $failIfNotFound ?
                $starterQuery->findOrFail($entity->id) :
                $starterQuery->find($entity->id);
        }

        return $entity instanceof $this->modelClass ?
            $entity :
            null;
    }

    public function relatedEntity(
        Model|int|string $entity,
        string $relationName,
        bool $failIfNotFound = false,
        bool $useQueryBuilder = false,
        ?string $queryBuilderModelClass = null,
    ): ?Model {
        $entity = $this->resolveEntity($entity, $failIfNotFound);

        $this->verifyRelation(methodName: $relationName);

        if ($useQueryBuilder && !empty($entity)) {
            $relatedEntity = $this->startQueryBuilder(query: $entity->$relationName(), for: $queryBuilderModelClass)
                ->buildQueryWithSelects()
                ->buildQueryWithIncludes()
                ->getQueryBuilder();

            return $relatedEntity?->first();
        }

        return $entity?->$relationName()?->get();
    }

    public function random(): ?object
    {
        return $this->all()?->random();
    }

    public function new(array $attributes = []): Model
    {
        return new $this->modelClass($attributes);
    }

    public function create(array $attributes = []): Model
    {
        return $this->modelClass::create($attributes);
    }

    public function update(
        Model|int|string $entity,
        array $attributes,
        array $options = [],
        bool $failIfNotFound = false
    ): ?Model {
        $entity = $this->resolveEntity($entity, $failIfNotFound);

        if (!empty($entity)) {
            $entity->update($attributes, $options);

            return $entity;
        }

        return null;
    }

    public function delete(
        Model|int|string $entity,
        bool $failIfNotFound = false
    ): ?bool {
        $entity = $this->resolveEntity($entity, $failIfNotFound);

        if (!empty($entity)) {
            return $entity->delete();
        }

        return null;
    }

    public function destroy(Collection|Model|array|int|string $idsOrEntities): int
    {
        return $this->modelClass::destroy($idsOrEntities);
    }

    public function truncate(): void
    {
        $this->modelClass::truncate();
    }

    public function attachEntity(
        Model|int|string $entity,
        string $relationName,
        Model|int|string $entityToAttach,
        array $attributes = [],
        bool $failIfNotFound = false,
        bool $loadRelation = false
    ): ?Model {
        $entity = $this->resolveEntity($entity, $failIfNotFound);

        $this->verifyRelation(methodName: $relationName);

        $entity?->$relationName()->attach(
            id: $entityToAttach->id ?? $entityToAttach,
            attributes: $attributes
        );

        return $loadRelation ?
            $entity?->load($relationName) :
            $entity;
    }

    public function updateAttachedEntity(
        Model|int|string $entity,
        string $relationName,
        Model|int|string $attachedEntity,
        array $attributes = [],
        bool $failIfNotFound = false,
        bool $loadRelation = false
    ): ?Model {
        $entity = $this->resolveEntity($entity, $failIfNotFound);

        $this->verifyRelation(methodName: $relationName);

        // Check if $attachedEntity is in fact attached
        $query = $entity?->$relationName()?->where('id', $attachedEntity->id ?? $attachedEntity);

        $attachedEntity = $failIfNotFound ?
            $query?->firstOrFail() :
            $query?->first();

        if (!empty($attachedEntity)) {
            $entity?->$relationName()?->updateExistingPivot(
                id: $attachedEntity->id,
                attributes: $attributes
            );

            return $loadRelation ?
                $entity?->load($relationName) :
                $entity;
        } else {
            return null;
        }
    }

    public function detachEntity(
        Model|int|string $entity,
        string $relationName,
        Model|int|string $attachedEntity,
        bool $failIfNotFound = false,
        bool $loadRelation = false
    ): ?Model {
        $entity = $this->resolveEntity($entity, $failIfNotFound);

        $this->verifyRelation(methodName: $relationName);

        // Check if $attachedEntity is in fact attached
        $query = $entity?->$relationName()?->where('id', $attachedEntity->id ?? $attachedEntity);

        $attachedEntity = $failIfNotFound ?
            $query?->firstOrFail() :
            $query?->first();

        if (!empty($attachedEntity)) {
            $entity?->$relationName()->detach($attachedEntity->id);

            return $loadRelation ?
                $entity?->load($relationName) :
                $entity;
        } else {
            return null;
        }
    }

    public function syncEntities(
        Model|int|string $entity,
        string $relationName,
        Collection|Model|array $entitiesToSync,
        bool $detaching = true,
        bool $failIfNotFound = false,
        bool $loadRelation = false
    ): ?Model {
        $entity = $this->resolveEntity($entity, $failIfNotFound);

        $this->verifyRelation(methodName: $relationName);

        $entity?->$relationName()?->sync($entitiesToSync, $detaching);

        return $loadRelation ?
            $entity?->load($relationName) :
            $entity;
    }

    public function where(
        Closure|string|array $column,
        $operator = null,
        $value = null,
        $boolean = 'and'
    ): Builder {
        return $this->modelClass::where($column, $operator, $value, $boolean);
    }

    public function whereNot(
        Closure|string|array $column,
        $operator = null,
        $value = null,
        string $boolean = 'and'
    ): Builder {
        return $this->modelClass::whereNot($column, $operator, $value, $boolean);
    }

    public function whereIn(
        Closure|string|array $column,
        mixed $values,
        string $boolean = 'and',
        bool $not = false
    ): Builder {
        return $this->modelClass::whereIn($column, $values, $boolean, $not);
    }

    public function whereRelation(
        string $relation,
        Closure|string|array|Expression $column,
        $operator = null,
        $value = null
    ): Builder|static {
        return $this->modelClass::whereRelation($relation, $column, $operator, $value);
    }

    public function orWhereRelation(
        string $relation,
        Closure|string|array|Expression $column,
        $operator = null,
        $value = null
    ): Builder|static {
        return $this->modelClass::orWhereRelation($relation, $column, $operator, $value);
    }

    public function has(
        Relation|string $relation,
        string $operator = '>=',
        int $count = 1,
        string $boolean = 'and',
        Closure $callback = null
    ): Builder|static {
        return $this->modelClass::has($relation, $operator, $count, $boolean, $callback);
    }

    public function orHas(
        string $relation,
        string $operator = '>=',
        int $count = 1
    ): Builder|static {
        return $this->modelClass::has($relation, $operator, $count);
    }

    public function whereHas(
        string $relation,
        Closure|null $callback = null,
        string $operator = '>=',
        int $count = 1
    ): Builder|static {
        return $this->modelClass::whereHas($relation, $callback, $operator, $count);
    }

    public function orWhereHas(
        string $relation,
        Closure|null $callback = null,
        string $operator = '>=',
        int $count = 1
    ): Builder|static {
        return $this->modelClass::orWhereHas($relation, $callback, $operator, $count);
    }

    public function startQueryBuilder(object $query = null, ?string $for = null): Repository
    {
        if (!empty($for)) {
            $this->queryBuilderModelClass = $for;
        } else {
            $this->queryBuilderModelClass = $this->modelClass;
        }

        if (!empty($query)) {
            $this->queryBuilder = QueryBuilder::for($query);
        } else {
            $this->queryBuilder = QueryBuilder::for($this->queryBuilderModelClass::query());
        }

        return $this;
    }

    public function buildQueryWithFilters(): Repository
    {
        if ($this->classImplementsInterface(Filterable::class, $this->queryBuilderModelClass)) {
            $allowedFilters = $this->queryBuilderModelClass::getAllowedFilters();

            if (!empty($allowedFilters)) {
                $this->queryBuilder = $this->queryBuilder
                    ->allowedFilters($allowedFilters);
            }
        }

        return $this;
    }

    public function buildQueryWithIncludes(): Repository
    {
        if ($this->classImplementsInterface(Includable::class, $this->queryBuilderModelClass)) {
            $allowedIncludes = $this->queryBuilderModelClass::getAllowedIncludes();
            $defaultIncludes = $this->queryBuilderModelClass::getDefaultIncludes();

            if (!empty($allowedIncludes)) {
                $this->queryBuilder = $this->queryBuilder
                    ->allowedIncludes($allowedIncludes);
            }

            if (!empty($defaultIncludes)) {
                $this->queryBuilder = $this->queryBuilder
                    ->with($defaultIncludes);
            }
        }

        return $this;
    }

    public function buildQueryWithSelects(): Repository
    {
        if ($this->classImplementsInterface(Selectable::class, $this->queryBuilderModelClass)) {
            $allowedFields = $this->queryBuilderModelClass::getAllowedFields();

            if (!empty($allowedFields)) {
                $this->queryBuilder = $this->queryBuilder
                    ->allowedFields($allowedFields);
            }
        }

        return $this;
    }

    public function buildQueryWithSorts(): Repository
    {
        if ($this->classImplementsInterface(Sortable::class, $this->queryBuilderModelClass)) {
            $allowedSorts = $this->queryBuilderModelClass::getAllowedSorts();
            $defaultSorts = $this->queryBuilderModelClass::getDefaultSorts();

            if (!empty($defaultSorts)) {
                $this->queryBuilder = $this->queryBuilder
                    ->defaultSort(...$defaultSorts);
            }

            if (!empty($allowedSorts)) {
                $this->queryBuilder = $this->queryBuilder
                    ->allowedSorts($allowedSorts);
            }
        }

        return $this;
    }

    public static function isId(mixed $value): bool
    {
        return is_int($value) || is_string($value);
    }

    public static function isNumeric(mixed $value): bool
    {
        if (is_int($value)) {
            return true;
        } elseif (is_string($value)) {
            return ctype_digit($value);
        } else {
            return false;
        }
    }

    public static function isSlug(mixed $value): bool
    {
        if (self::isNumeric($value)) {
            return false;
        } elseif (is_string($value)) {
            preg_match(
                '/^[a-z0-9]+(?:-[a-z0-9]+)*$/m',
                $value,
                $matches,
                PREG_OFFSET_CAPTURE,
                0
            );

            return !empty($matches);
        } else {
            return false;
        }
    }

    public static function isArrayOrCollection(mixed $value): bool
    {
        return self::isArray($value) || self::isCollection($value);
    }

    public static function isArray(mixed $value): bool
    {
        return is_array($value);
    }

    public static function isCollection(mixed $value): bool
    {
        return $value instanceof Collection;
    }

    public static function isEloquentCollection(mixed $value): bool
    {
        return $value instanceof EloquentCollection;
    }

    public static function isIdArrayOrCollection(mixed $value): bool
    {
        if (self::isArray($value)) {
            $value = collect($value);
        }

        if (self::isCollection($value)) {
            return $value->every(fn ($val, $key) => self::isId($val));
        } else {
            return false;
        }
    }

    protected function verifyRelation(string $methodName): void
    {
        if (!method_exists($this->modelClass, $methodName)) {
            throw new RelationMethodNotFoundException();
        }
    }
}
