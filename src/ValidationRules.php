<?php

namespace Azolee\Validator;

use Azolee\Validator\Helpers\ArrayHelper;

class ValidationRules
{
    public const CUSTOM_RULE = 'custom_rule';

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function array(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return is_array($data);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function string(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return is_null($data) || is_string($data);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function numeric(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return is_null($data) || is_numeric($data);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function boolean(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        if (is_bool($data)) {
            return true;
        }
        return is_null($data) || filter_var($data, FILTER_VALIDATE_BOOLEAN) === true;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function not_null(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return !is_null($data);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function not_equals_field(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return $data !== ArrayHelper::parseNestedData($dataToValidate, $value);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function email(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function url(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return filter_var($data, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function min(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return is_numeric($data) && $data >= $value;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function max(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return is_numeric($data) && $data <= $value;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function in(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return in_array($data, explode(',', $value));
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function date(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return strtotime($data) !== false;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function alpha(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return ctype_alpha($data);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function alpha_num(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return ctype_alnum($data);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function digits(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return is_numeric($data) && strlen((string)$data) == $value;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function digits_between(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        [$min, $max] = explode(',', $value);
        $length = strlen((string)$data);
        return is_numeric($data) && $length >= $min && $length <= $max;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function same(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return $data === ArrayHelper::parseNestedData($dataToValidate, $value);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function different(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return $data !== ArrayHelper::parseNestedData($dataToValidate, $value);
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function ip(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return filter_var($data, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function json(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        json_decode($data);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function regex(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return preg_match($value, $data) === 1;
    }

    /**
     * @param mixed $dataToValidate
     * @param string|null $key
     * @param mixed|null $value
     * @return bool
     */
    public static function required(mixed $dataToValidate, ?string $key = null, mixed $value = null): bool
    {
        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }
        return !empty($data);
    }
}