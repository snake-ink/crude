<?php

namespace SnakeInk\Crude\Concerns;

use ReflectionClass;

trait ChecksIfClassImplementsAnother {
    protected function classImplementsInterface(
        string $interface, 
        ?string $class = null
    ): bool {
        return (new ReflectionClass($class ?? $this::class))
            ->implementsInterface($interface);
    }
}