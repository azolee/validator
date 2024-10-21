<?php

namespace Azolee\Validator;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;
use Azolee\Validator\Helpers\ErrorsHelper;

class ValidationErrorBag implements ValidationErrorBagInterface
{
    private array $list = [
        ValidationRules::CUSTOM_RULE => 'The :attribute is invalid.',
        'numeric' => 'The :attribute is not a valid number.',
        'string' => 'The :attribute must be a string.',
        'boolean' => 'The :attribute must be a boolean.',
        'not_null' => 'The :attribute must not be null.',
        'not_equals_field' => 'The :attribute does not equals :key.',
    ];

    /**
     * @return array
     * */
    public function getErrorFor(array|string $rules, string $attribute): string
    {
        return ErrorsHelper::getError($this->list, $rules, $attribute);
    }
}