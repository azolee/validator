<?php

namespace Azolee\Validator\Helpers;

class ArrayHelper
{
    public static function parseNestedData(array $dataToValidate, string $keys): mixed
    {
        $result = $dataToValidate;
        $keysArr = explode('.', $keys);
        foreach ($keysArr as $index => $key) {
            if ($key === '*') {
                if (!is_array($result)) {
                    return [];
                }
                foreach ($result as $item) {
                    if (is_array($item)) {
                        return static::parseNestedData($item, implode('.', array_slice($keysArr, $index + 1)));
                    }
                }
                return "";
            }
            if (array_key_exists($key, $result)) {
                $result = $result[$key];
            } else {
                return [];
            }
        }

        return $result;
    }
}