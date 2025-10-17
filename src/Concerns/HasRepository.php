<?php

namespace SnakeInk\Crude\Concerns;

use SnakeInk\Crude\Abstracts\Repository;

/**
 * @template TRepository of \SnakeInk\Crude\Abstracts\Repository;
 */
trait HasRepository
{
    /**
     * Get a new repository instance for the model.
     *
     * @return TRepository
     */
    public static function repository()
    {
        return static::newRepository() ?? Repository::repositoryForModel(static::class);
    }

    /**
     * Create a new repository instance for the model.
     *
     * @return TRepository|null
     */
    protected static function newRepository()
    {
        if (isset(static::$repository)) {
            return static::$repository::new();
        }

        return null;
    }
}
