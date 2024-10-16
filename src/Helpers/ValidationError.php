<?php

namespace Azolee\Validator;

class ValidationError
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
        $errors = [];
        if (!is_array($rules)) {
            $rules = [$rules];
        }

        foreach ($rules as $rule) {
            $additionalAttribute = "";
            if (str_contains($rule, ':')) {
                [$rule, $additionalAttribute] = explode(':', $rule, 2);
            }

            $error = str_ireplace(':attribute', $attribute, $this->list[$rule] ?? "The $attribute is invalid.");
            $errors[] = str_ireplace(':key', $additionalAttribute, $error);
        }
        return join(", ", $errors);
    }
}