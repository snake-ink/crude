<?php

namespace SnakeInk\Crude\Concerns;

trait HasValidationErrorMessage
{
    use InteractsWithCustomAttributeTranslations;

    public function message(string $attribute, ?string $errorMessageKey = null)
    {
        $errorMessageKey = $errorMessageKey ?? $this::class::ERROR_MESSAGE_KEY;

        return trans(
            key: 'sss::validation.'.$errorMessageKey,
            replace: ['attribute' => str_replace('_', ' ', $this->finalAttributeTranslation($attribute))]
        );
    }
}
