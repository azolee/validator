<?php

namespace Azolee\Validator\Helpers;

class ErrorsHelper
{
// src/Helpers/ErrorsHelper.php
    public static function getError(array $list, array|string $rules, string $attribute, array $extraParams = []): string
    {
        $errors = [];
        if (!is_array($rules)) {
            $rules = [$rules];
        }

        foreach ($rules as $rule) {
            if (str_contains($rule, ':')) {
                list($rule, $value) = explode(':', $rule, 2);
                $extraParams['value'] = $value;
            }

            $error = str_ireplace(':attribute', $attribute, $list[$rule] ?? "The $attribute is invalid.");
            $extraParams = array_reverse($extraParams);
            foreach ($extraParams as $key => $value) {
                $error = str_ireplace(":$key", $value, $error);
            }
            $errors[] = $error;
        }
        return join(", ", $errors);
    }
}