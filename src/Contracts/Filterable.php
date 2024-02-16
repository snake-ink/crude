<?php

namespace SnakeInk\Crude\Contracts;

interface Filterable
{
    public static function getAllowedFilters(): array;
}
