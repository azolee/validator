<?php

namespace Azolee\Validator;

use Azolee\Validator\Validators\ImageValidator;

class Rule
{
    public static function image(
        mixed $data,
        array|string $mimeTypes = null,
        int $maxSize = null,
        float $minRatio = null,
        float $maxRatio = null,
        int $minWidth = null,
        int $maxWidth = null,
        int $minHeight = null,
        int $maxHeight = null
    ): bool
    {
        $mimeTypes = is_array($mimeTypes) ? $mimeTypes : explode("|", $mimeTypes);
        $imageValidator = new ImageValidator(
            $mimeTypes,
            (int)$maxSize,
            (float)$minRatio,
            (float)$maxRatio,
            (int)$minWidth,
            (int)$maxWidth,
            (int)$minHeight,
            (int)$maxHeight
        );

        return $imageValidator->validate($data);
    }
}