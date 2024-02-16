<?php

namespace SnakeInk\Crude\Contracts;

interface Includable
{
    public static function getAllowedIncludes(): array;

    public static function getDefaultIncludes(): string|array;
}
