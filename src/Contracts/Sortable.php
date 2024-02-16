<?php

namespace SnakeInk\Crude\Contracts;

interface Sortable
{
    public static function getDefaultSorts(): array;

    public static function getAllowedSorts(): array;
}
