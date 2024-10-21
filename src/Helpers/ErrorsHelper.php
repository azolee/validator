<?php

namespace Azolee\Validator\Helpers;

class ErrorsHelper
{
    public static function getError(array $list, array|string $rules, string $attribute): string
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

            $error = str_ireplace(':attribute', $attribute, $list[$rule] ?? "The $attribute is invalid.");
            $errors[] = str_ireplace(':key', $additionalAttribute, $error);
        }
        return join(", ", $errors);
    }
}