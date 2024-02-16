<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator as LaravelValidatorFacade;
use Illuminate\Validation\Validator as LaravelValidator;

abstract class Validator
{
    protected $inputData;
    protected $contextData;

    public function __construct(private Container $container)
    {
    }

    public function buildValidatorArgs(
        array $inputData,
        string|array $rulesMethod,
        array $contextData = []
    ): array {
        $this->inputData = $inputData;
        $this->contextData = $contextData;

        $libraryCustomAttributes = Lang::get('sss::validation.attributes');
        $projectCustomAttributes = Lang::get('validation.attributes');

        if (!self::isCustomAttributesValid($libraryCustomAttributes)) {
            $libraryCustomAttributes = [];
        }

        if (!self::isCustomAttributesValid($projectCustomAttributes)) {
            $projectCustomAttributes = [];
        }

        return [
            'data' => $inputData,
            'rules' => is_string($rulesMethod)
                ? $this->container->call([$this, $rulesMethod])
                : $rulesMethod,
            'attributes' => [
                ...$libraryCustomAttributes,
                ...$projectCustomAttributes,
            ],
        ];
    }

    public function makeLaravelValidator(
        array $inputData,
        string|array $rulesMethod,
        array $contextData = []
    ): LaravelValidator {
        return LaravelValidatorFacade::make(...$this->buildValidatorArgs(
            inputData: $inputData,
            rulesMethod: $rulesMethod,
            contextData: $contextData
        ));
    }

    public function validate(
        array $inputData,
        string|array $rulesMethod,
        array $contextData = [],
    ): array {
        return $this->makeLaravelValidator(
            inputData: $inputData,
            rulesMethod: $rulesMethod,
            contextData: $contextData
        )->validate();
    }

    /**
     * Set the container implementation.
     */
    public function setContainer(Container $container): Validator
    {
        $this->container = $container;

        return $this;
    }

    private static function isCustomAttributesValid(mixed $attributes): bool
    {
        return is_array($attributes);
    }
}
