<?php

namespace SnakeInk\Crude\Concerns;

use Illuminate\Support\Facades\Lang;

trait InteractsWithCustomAttributeTranslations
{
    private function finalAttributeTranslation(string $attribute): string
    {
        $localCustomAttributeKey = 'validation.attributes.'.$attribute;
        $libraryCustomAttributeKey = 'sss::'.$localCustomAttributeKey;

        $localAttributeTranslation = Lang::get($localCustomAttributeKey);
        $libraryAttributeTranslation = Lang::get($libraryCustomAttributeKey);

        $isLocalAttributeTranslationValid = !$this->invalidCustomAttributeTranslation(
            value: $localAttributeTranslation,
            attributeKey: $localCustomAttributeKey
        );

        if ($isLocalAttributeTranslationValid) {
            return $localAttributeTranslation;
        }

        $isLibraryAttributeTranslationValid = !$this->invalidCustomAttributeTranslation(
            value: $libraryAttributeTranslation,
            attributeKey: $libraryCustomAttributeKey
        );

        if ($isLibraryAttributeTranslationValid) {
            return $libraryAttributeTranslation;
        }

        return $attribute;
    }

    private function invalidCustomAttributeTranslation(mixed $value, string $attributeKey): bool
    {
        return !is_string($value) || empty($value) || $value === $attributeKey;
    }
}
