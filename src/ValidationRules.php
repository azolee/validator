<?php

namespace Azolee\Validator;

use Azolee\Validator\Helpers\ArrayHelper;

class ValidationRules
{
    public const CUSTOM_RULE = 'custom_rule';

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function array(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_array($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function string(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_null($data) || is_string($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function numeric(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_null($data) || is_numeric($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function boolean(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_bool($data)) {
            return true;
        }
        return is_null($data) || filter_var($data, FILTER_VALIDATE_BOOLEAN) === true;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function not_null(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return !is_null($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function email(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function url(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return filter_var($data, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function min(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_numeric($data) && $data >= $value;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function max(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_numeric($data) && $data <= $value;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function in(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return in_array($data, explode(',', $value));
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function date(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return strtotime($data) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function alpha(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return extension_loaded('ctype') ? ctype_alpha($data) : (preg_match('/^[a-zA-Z]+$/', $data) === 1);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function alpha_num(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return extension_loaded('ctype') ? ctype_alnum($data) : (preg_match('/^[a-zA-Z0-9]+$/', $data) === 1);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function digits(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_numeric($data) && strlen((string)$data) == $value;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function digits_between(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        [$min, $max] = explode(',', $value);
        $length = strlen((string)$data);
        return is_numeric($data) && $length >= $min && $length <= $max;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function same(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $fieldToCompare = ArrayHelper::parseNestedData($dataToValidate, $value)[0] ?? ['value' => null];
        return $data === $fieldToCompare['value'];
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function different(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return !static::same($data, $key, $value, $dataToValidate);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function ip(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return filter_var($data, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function json(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        json_decode($data);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function regex(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return preg_match($value, $data) === 1;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function required(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return !empty($data);
    }
}