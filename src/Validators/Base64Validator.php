<?php

namespace Azolee\Validator\Validators;

use Azolee\Validator\Helpers\ArrayHelper;

class Base64Validator
{
    /**
     * Validates that the data is a valid base64 encoded string.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function validateString(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (!is_string($data)) {
            return false;
        }
        $decodedData = '';
        $chunkSize = static::getChunkSize($value);

        for ($i = 0; $i < strlen($data); $i += $chunkSize) {
            $chunk = substr($data, $i, $chunkSize);
            $decodedChunk = base64_decode($chunk, true);

            if ($decodedChunk === false) {
                return false;
            }

            $decodedData .= $decodedChunk;
        }

        return base64_encode($decodedData) === $data;
    }

    /**
     * Validates that the data is a valid base64 encoded image.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function validateImage(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $pattern = '/^data:image\/[a-zA-Z]+;base64,/';
        if (!preg_match($pattern, $data)) {
            return false;
        }

        $base64String = preg_replace($pattern, '', $data);
        return static::validateString($base64String);
    }

    /**
     * @param mixed|null $value
     * @return int
     */
    private static function getChunkSize(mixed $value = null): int
    {
        $chunkSize = 8192;
        if ($value) {
            $config = ArrayHelper::transformStringToArray($value);
            $chunkSize = intval(array_is_list($config) ? end($config) : ($config['chunk_size'] ?? $chunkSize));
        }
        return $chunkSize;
    }
}
