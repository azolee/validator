<?php

namespace Azolee\Validator\Helpers;

class ArrayHelper
{
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
}