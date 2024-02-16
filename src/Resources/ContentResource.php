<?php

namespace SnakeInk\Crude\Resources;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use SnakeInk\Crude\Abstracts\Resource;

class ContentResource extends Resource
{
    public static function toJson(
        mixed $resources = null,
        mixed $metadata = null,
        ?string $message = null,
        ?string $messageKey = null,
        ?int $pluralizationNumber = null,
        array $translationReplacements = [],
        ?string $locale = null,
        bool $loadQueryBuilderInfo = false
    ): JsonResponse {
        return self::jsonResource(
            status: self::emptyResources($resources) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK,
            resources: $resources,
            metadata: $metadata,
            message: $message,
            messageKey: $messageKey,
            pluralizationNumber: $pluralizationNumber,
            translationReplacements: $translationReplacements,
            locale: $locale,
            loadQueryBuilderInfo: $loadQueryBuilderInfo
        );
    }
}
