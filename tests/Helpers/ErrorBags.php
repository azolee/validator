<?php

namespace Tests\Helpers;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;
use Azolee\Validator\Helpers\ErrorsHelper;

class ErrorBags
{
    public static function getCustomErrorBag(array $customList = []): ValidationErrorBagInterface
    {
        return new class($customList) implements ValidationErrorBagInterface {
            private array $list;

            public function __construct(?array $customList = null)
            {
                $this->list = $customList ?? [
                    'numeric' => 'The :attribute is not a valid number.',
                    'string' => 'The :attribute must be a string.',
                ];
            }

            public function getErrorFor(array|string $rules, string $attribute): string
            {
                return ErrorsHelper::getError($this->list, $rules, $attribute);
            }
        };
    }
}