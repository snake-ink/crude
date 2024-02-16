<?php

namespace SnakeInk\Crude\Resources;

use Illuminate\Http\Response;
use SnakeInk\Crude\Abstracts\Resource;

class CreatedResource extends Resource
{
    public static function toJson(
        mixed $resources = null,
        mixed $metadata = null,
        ?string $message = null,
        ?string $messageKey = null,
        ?int $pluralizationNumber = null,
        array $translationReplacements = [],
        ?string $locale = null,
    ) {
        return self::jsonResource(
            status: Response::HTTP_CREATED,
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
