<?php

namespace SnakeInk\Crude\Resources;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use SnakeInk\Crude\Abstracts\Resource;

class GenericResource extends Resource
{
    public static function toJson(
        int $status = Response::HTTP_OK,
        mixed $resources = null,
        mixed $metadata = null,
        ?string $message = null,
        ?string $messageKey = null,
        ?int $pluralizationNumber = null,
        array $translationReplacements = [],
        ?string $locale = null,
    ): JsonResponse {
        return self::jsonResource(
            status: $status,
            resources: $resources,
            metadata: $metadata,
            message: $message,
            messageKey: $messageKey,
            pluralizationNumber: $pluralizationNumber,
            translationReplacements: $translationReplacements,
            locale: $locale
        );
    }
}
