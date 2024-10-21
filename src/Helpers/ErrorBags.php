<?php

namespace Azolee\Validator\Helpers;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;
use Azolee\Validator\ValidationRules;

class ErrorBags
{
    public static function getCustomErrorBag(?array $customList = null): ValidationErrorBagInterface
    {
        return new class($customList) implements ValidationErrorBagInterface {
            private array $list;

            public function __construct(?array $customList = null)
            {
                $this->list = $customList ?? [
                    ValidationRules::CUSTOM_RULE => 'The :attribute is invalid.',
                    'numeric' => 'The :attribute is not a valid number.',
                    'string' => 'The :attribute must be a string.',
                    'boolean' => 'The :attribute must be a boolean.',
                    'not_null' => 'The :attribute must not be null.',
                    'not_equals_field' => 'The :attribute does not equals :key.',
                ];
            }

            public function getErrorFor(array|string $rules, string $attribute): string
            {
                return ErrorsHelper::getError($this->list, $rules, $attribute);
            }
        };
    }
}