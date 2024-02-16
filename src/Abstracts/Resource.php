<?php

namespace SnakeInk\Crude\Abstracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class Resource
{
    public static function jsonResource(
        int $status,
        mixed $resources = null,
        mixed $metadata = null,
        ?string $message = null,
        ?string $messageKey = null,
        ?int $pluralizationNumber = null,
        array $translationReplacements = [],
        ?string $locale = null,
        mixed $errors = null,
        mixed $debug = null,
        bool $loadQueryBuilderInfo = false
    ): JsonResponse {
        $responseDefinition = [];

        $resourcesCount = self::resourcesCount($resources);

        if ($resourcesCount > 0) {
            $responseDefinition['resources'] = $resources;
        }

        if (!empty($metadata)) {
            $responseDefinition['metadata'] = $metadata;
        }

        if (!empty($message)) {
            $responseDefinition['message'] = $message;
        } elseif (!empty($messageKey)) {
            $number = $pluralizationNumber ?? $resourcesCount;

            if ($number > 0) {
                $message = trans_choice(
                    key: $messageKey,
                    number: $number,
                    replace: $translationReplacements,
                    locale: $locale
                );

                $responseDefinition['message'] = $message;
            }
        }

        if (!empty($errors)) {
            $responseDefinition['errors'] = $errors;
        }

        if (!empty($debug)) {
            $responseDefinition['debug'] = $debug;
        }

        if ($loadQueryBuilderInfo) {
            if ($resources instanceof LengthAwarePaginator) {
                $tentativeCollection = $resources->getCollection();
            } else {
                $tentativeCollection = $resources;
            }

            if ($tentativeCollection instanceof EloquentCollection) {
                if ($tentativeCollection->isNotEmpty()) {
                    $entityClass = get_class($tentativeCollection->first());

                    $responseDefinition['info'] = null;

                    if (method_exists($entityClass, 'getDefaultIncludes')) {
                        $responseDefinition['info']['default_includes'] = $entityClass::getDefaultIncludes();
                    }

                    if (method_exists($entityClass, 'getDefaultSorts')) {
                        $responseDefinition['info']['default_sorts'] = $entityClass::getDefaultSorts();
                    }
                }
            }
        }

        return new JsonResponse($responseDefinition, $status);
    }

    public static function resourcesCount(mixed $resources): int
    {
        return match (true) {
            empty($resources) => 0,
            $resources instanceof LengthAwarePaginator => $resources?->total(),
            $resources instanceof Collection => $resources?->count(),
            is_array($resources) => count($resources),
            default => 1
        };
    }

    public static function emptyResources(mixed $resources): bool
    {
        return self::resourcesCount($resources) <= 0;
    }

    public static function notEmptyResources(mixed $resources): bool
    {
        return !self::emptyResources($resources);
    }
}
