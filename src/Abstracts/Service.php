<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use SnakeInk\Crude\Exceptions\PolicyNotFoundException;
use SnakeInk\Crude\Exceptions\ValidatorMethodNotFound;
use Illuminate\Validation\Validator as LaravelValidator;
use SnakeInk\Crude\Exceptions\InvalidValidatorException;
use SnakeInk\Crude\Exceptions\InvalidRepositoryException;
use SnakeInk\Crude\Exceptions\IllegalPolicyEntityException;
use SnakeInk\Crude\Exceptions\InvalidPolicyClassNameException;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

abstract class Service
{
    public function __construct(
        protected string $policyClass,
        protected object $validator,
        protected object $repository,
        protected string $modelClass = Model::class,
        protected bool $throwAuthorizationException = true,
        protected bool $dispatchEvents = true,
        protected Authenticatable|Model|null $userActor = null,
    ) {
        $this->userActor = $userActor ?? Auth::user();

        $this->verifyPolicyClass();
        $this->verifyValidator();
        $this->verifyRepository();
    }

    public function getThrowAuthorizationException(): bool
    {
        return $this->throwAuthorizationException;
    }

    public function setThrowAuthorizationException(bool $throwAuthorizationException): void
    {
        $this->throwAuthorizationException = $throwAuthorizationException;
    }

    public function getUserActor(): Model
    {
        return $this->userActor;
    }

    public function setUserActor(Model $userActor): void
    {
        $this->userActor = $userActor;
    }

    public function index(
        bool $useQueryBuilder = false,
        ?string $queryBuilderModelClass = null,
        ?int $perPage = null,
        ?array $paginationArguments = null,
        array $authorizationContextData = [],
        array $authorizationArguments = [],
        array $whereArguments = [],
        ?object $starterQuery = null
    ): Collection|LengthAwarePaginator|null {
        if (empty($authorizationArguments)) {
            $this->authorize(ability: 'viewAny', contextData: $authorizationContextData);
        } else {
            $this->authorize(...$authorizationArguments);
        }

        if (!empty($whereArguments)) {
            $starterQuery = $this->repository->where(...$whereArguments);
        }

        $starterQuery = $this->buildAuthorizedIndexQuery(
            starterQuery: $starterQuery,
            userActor: $authorizationArguments['userActor'] ?? null,
            alternatePolicyClass: $authorizationArguments['alternatePolicyClass'] ?? null,
            alternateModelClass: $authorizationArguments['alternateModelClass'] ?? null
        );


        $entities = $this->repository->all(
            useQueryBuilder: $useQueryBuilder,
            queryBuilderModelClass: $queryBuilderModelClass,
            perPage: $perPage,
            paginationArguments: $paginationArguments,
            starterQuery: $starterQuery
        );

        return $entities;
    }

    public function show(
        int|string $id,
        bool $failIfNotFound = false,
        array $columns = ['*'],
        bool $useQueryBuilder = false,
        array $authorizationContextData = [],
        array $authorizationArguments = [],
        ?object $starterQuery = null
    ): ?Model {
        $entity = $this->repository->find(
            id: $id,
            failIfNotFound: $failIfNotFound,
            columns: $columns,
            useQueryBuilder: $useQueryBuilder,
            starterQuery: $starterQuery
        );

        if (empty($entity)) {
            return null;
        }

        if (empty($authorizationArguments)) {
            $this->authorize(
                ability: 'view',
                entity: $entity,
                contextData: $authorizationContextData
            );
        } else {
            $this->authorize(...$authorizationArguments);
        }

        return $entity;
    }

    public function store(
        array $attributes = [],
        array $validationContextData = [],
        array $validationArguments = [],
        array $authorizationContextData = [],
        array $authorizationArguments = []
    ): Model {
        if (empty($validationArguments)) {
            $this->validate(
                inputData: $attributes,
                rulesMethod: 'store',
                contextData: $validationContextData
            );
        } else {
            $this->validate(...$validationArguments);
        }

        if (empty($authorizationArguments)) {
            $this->authorize(
                ability: 'create',
                contextData: [$attributes, ...$authorizationContextData],
            );
        } else {
            $this->authorize(...$authorizationArguments);
        }

        $entity = $this->repository->create($attributes);

        return $entity;
    }

    public function update(
        Model|int|string $entity,
        array $attributes,
        array $options = [],
        bool $failIfNotFound = false,
        array $validationContextData = [],
        array $validationArguments = [],
        array $authorizationContextData = [],
        array $authorizationArguments = [],
        ?object $starterQuery = null
    ): ?Model {
        if (empty($validationArguments)) {
            $this->validate(
                inputData: $attributes,
                rulesMethod: 'update',
                contextData: $validationContextData
            );
        } else {
            $this->validate(...$validationArguments);
        }

        $entity = $this->repository->resolveEntity(
            entity: $entity,
            failIfNotFound: $failIfNotFound,
            starterQuery: $starterQuery
        );

        if (empty($entity)) {
            return null;
        }

        if (empty($authorizationArguments)) {
            $this->authorize(
                ability: 'update',
                entity: $entity,
                contextData: [$attributes, ...$authorizationContextData],
            );
        } else {
            $this->authorize(...$authorizationArguments);
        }

        $entity = $this->repository->update(
            entity: $entity,
            attributes: $attributes,
            options: $options,
            failIfNotFound: $failIfNotFound
        );

        return $entity;
    }

    public function destroy(
        Collection|Model|array|int|string $entities,
        bool $failIfNotFound = false,
        array $authorizationContextData = [],
        array $authorizationArguments = [],
        ?object $starterQuery = null
    ): int|bool|null {
        if (Repository::isId($entities)) {
            $entity = $this->repository->find(
                id: $entities,
                failIfNotFound: $failIfNotFound,
                starterQuery: $starterQuery
            );
        }

        if (empty($authorizationArguments)) {
            $this->authorize(
                ability: 'delete',
                entity: $entity ?? $entities,
                contextData: $authorizationContextData,
            );
        } else {
            $this->authorize(...$authorizationArguments);
        }

        if (Repository::isArrayOrCollection($entities)) {
            $result = $this->repository->destroy($entities); // Delete Multiple Entities
        } else {
            $result = $this->repository->delete($entity ?? $entities); // Delete Single Entity
        }

        return $result;
    }

    public function count(
        bool $useQueryBuilder = false,
        ?String $queryBuilderModelClass = null,
        array $authorizationContextData = [],
        array $authorizationArguments = [],
        array $whereArguments = [],
        ?object $starterQuery = null,
    ): int {
        if (empty($authorizationArguments)) {
            $this->authorize(ability: 'viewAny', contextData: $authorizationContextData);
        } else {
            $this->authorize(...$authorizationArguments);
        }

        if (! empty($whereArguments)) {
            $starterQuery = $this->repository->where(...$whereArguments);
        }

        $starterQuery = $this->buildAuthorizedIndexQuery(
            starterQuery: $starterQuery,
            userActor: $authorizationArguments['userActor'] ?? null,
            alternatePolicyClass: $authorizationArguments['alternatePolicyClass'] ?? null,
            alternateModelClass: $authorizationArguments['alternateModelClass'] ?? null
        );

        if ($useQueryBuilder) {
            $query = $this->repository
                ->startQueryBuilder(query: $starterQuery, for: $queryBuilderModelClass)
                ->buildQueryWithSelects()
                ->buildQueryWithIncludes()
                ->buildQueryWithFilters()
                ->buildQueryWithSorts()
                ->getQueryBuilder();
        } else {
            $query = $starterQuery ?? $this->modelClass::query();
        }

        return $query->count();
    }

    protected function authorize(
        string $ability,
        Model|int|string $entity = null,
        array $contextData = [],
        mixed $userActor = null,
        ?bool $throwAuthorizationException = null,
        ?string $alternateModelClass = null,
        ?string $alternatePolicyClass = null
    ): ?Response {
        if (empty($entity)) {
            $entity = $alternateModelClass ?? $this->modelClass;
        } elseif (empty($alternateModelClass)) {
            $entity = $this->repository->resolveEntity($entity, true);
        }

        if (empty($userActor)) {
            $userActor = $this->userActor;
        }

        if (empty($throwAuthorizationException)) {
            $throwAuthorizationException = $this->throwAuthorizationException;
        }

        if (!empty($alternatePolicyClass)) {
            $this->verifyPolicyClass(
                alternatePolicyClass: $alternatePolicyClass,
                alternateModelClass: $alternateModelClass
            );
        }

        $this->verifyPolicyEntity(
            entity: $entity,
            alternateModelClass: $alternateModelClass,
        );

        if ($this->policyMethodExists($ability, $alternatePolicyClass)) {
            $arguments = [$entity, ...$contextData];

            $gateWithUserActor = Gate::forUser($userActor ?? Auth::user());

            return $throwAuthorizationException 
                ? $gateWithUserActor->authorize($ability, $arguments) 
                : $gateWithUserActor->inspect($ability, $arguments);
        }

        return null;
    }

    protected function buildAuthorizedIndexQuery(
        ?object $starterQuery = null,
        mixed $userActor = null,
        ?string $alternatePolicyClass = null,
        ?string $alternateModelClass = null
    ): ?BuilderContract {
        $policyClass = $alternatePolicyClass ?? $this->policyClass;

        if (!empty($alternatePolicyClass)) {
            $this->verifyPolicyClass(
                alternatePolicyClass: $alternatePolicyClass,
                alternateModelClass: $alternateModelClass
            );
        }

        if ($this->policyMethodExists('buildViewAnyFilteredQuery', $alternatePolicyClass)) {
            return $policyClass::buildViewAnyFilteredQuery(
                actor: $userActor ?? $this->userActor ?? Auth::user(),
                starterQuery: $starterQuery,
            );
        }

        return $starterQuery;
    }

    private function policyMethodExists(string $abilityMethodName, ?string $alternatePolicyClass = null)
    {
        return method_exists($alternatePolicyClass ?? $this->policyClass, $abilityMethodName);
    }

    public function validate(
        array $inputData,
        string|array $rulesMethod,
        array $contextData = []
    ): ?array {
        return $this
            ->makeLaravelValidator(
                inputData: $inputData,
                rulesMethod: $rulesMethod,
                contextData: $contextData
            )
            ->validate();
    }

    protected function makeLaravelValidator(
        array $inputData,
        string|array $rulesMethod,
        array $contextData = []
    ): ?LaravelValidator {
        if (is_string($rulesMethod)) {
            $this->verifyValidatorMethod($rulesMethod);
        }

        return $this->validator
            ->makeLaravelValidator(
                inputData: $inputData,
                rulesMethod: $rulesMethod,
                contextData: $contextData
            );
    }

    private function verifyValidatorMethod(string $rulesMethodName): void
    {
        if (!method_exists($this->validator, $rulesMethodName)) {
            throw new ValidatorMethodNotFound();
        }
    }

    private function verifyValidator(): void
    {
        if (!is_subclass_of($this->validator, Validator::class)) {
            throw new InvalidValidatorException();
        }
    }

    private function verifyRepository(): void
    {
        if (!is_subclass_of($this->repository, Repository::class)) {
            throw new InvalidRepositoryException();
        }
    }

    private function verifyPolicyClass(
        ?string $alternatePolicyClass = null,
        ?string $alternateModelClass = null
    ): void {
        $modelClass = $alternateModelClass ?? $this->modelClass;
        $policyClass = $alternatePolicyClass ?? $this->policyClass;

        if (!class_exists($policyClass)) {
            throw new PolicyNotFoundException();
        }

        $modelClassBasename = class_basename($modelClass);
        $policyClassBasename = class_basename($policyClass);

        if ($modelClassBasename !== Str::beforeLast($policyClassBasename, 'Policy')) {
            throw new InvalidPolicyClassNameException();
        }
    }

    private function verifyPolicyEntity(
        Model|string $entity,
        ?string $alternateModelClass = null,
    ): void {
        $modelClass = $alternateModelClass ?? $this->modelClass;

        if ($entity instanceof Model && !$entity instanceof $modelClass) {
            throw new IllegalPolicyEntityException();
        } elseif (!$entity instanceof Model && $entity !== $modelClass) {
            throw new IllegalPolicyEntityException();
        }
    }
}
