<?php

namespace Azolee\Validator\Helpers;

class ArrayHelper
{
    /**
     * @param array $dataToValidate
     * @param string $keys
     * @param string $parentKey
     * @return array|array[]
     */
    public static function parseNestedData(array $dataToValidate, string $keys, string $parentKey = ''): array
    {
        $result = $dataToValidate;
        $keysArr = explode('.', $keys);
        foreach ($keysArr as $index => $key) {
            if ($key === '*') {
                if (!is_array($result)) {
                    return [];
                }
                $allResults = [];
                foreach ($result as $i => $item) {
                    if (is_array($item)) {
                        $nestedResults = static::parseNestedData($item, implode('.', array_slice($keysArr, $index + 1)), $parentKey . $i . '.');
                        foreach ($nestedResults as $nestedResult) {
                            $allResults[] = $nestedResult;
                        }
                    }
                }
                return $allResults;
            }
            if (array_key_exists($key, $result)) {
                $result = $result[$key];
                $parentKey .= $key . '.';
            } else {
                return [];
            }
        }

        return [['value' => $result, 'key' => rtrim($parentKey, '.'), 'type' => gettype($result)]];
    }

    /**
     * @param string $input
     * @return array
     */
    public static function transformStringToArray(string $input): array
    {
        $result = [];
        $elements = explode(',', $input);

        foreach ($elements as $element) {
            if (str_contains($element, ':')) {
                list($key, $value) = explode(':', $element, 2);
                $result[$key] = is_numeric($value) ? (int)$value : $value;
            } else {
                $result[] = $element;
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static function prependStringToNumericKeys(array $array, string $prefix): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $result[$prefix . ($key > 0 ? $key : "")] = $value;
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * @param array $input
     * @return array
     */
    public static function normalizeArray(array $input): array
    {
        $result = [];
        foreach ($input as $item) {
            $keys = explode('.', $item['key']);
            $current = &$result;
            foreach ($keys as $key) {
                if (!isset($current[$key])) {
                    $current[$key] = [];
                }
                $current = &$current[$key];
            }
            $current = $item['value'];
        }
        return $result;
    }
}