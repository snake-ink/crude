<?php

namespace SnakeInk\Crude\Resources;

use Illuminate\Http\Response;
use SnakeInk\Crude\Abstracts\Resource;

class ErrorResource extends Resource
{
    public static function toJson(
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?string $message = null,
        ?string $messageKey = null,
        ?int $pluralizationNumber = null,
        array $translationReplacements = [],
        ?string $locale = null,
        mixed $errors = null,
        mixed $debug = null
    ) {
        return self::jsonResource(
            status: $status,
            message: $message,
            messageKey: $messageKey,
            pluralizationNumber: $pluralizationNumber,
            translationReplacements: $translationReplacements,
            locale: $locale,
            errors: $errors,
            debug: $debug
        );
    }
}
