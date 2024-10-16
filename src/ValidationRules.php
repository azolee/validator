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
}