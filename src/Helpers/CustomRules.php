<?php

namespace Azolee\Validator\Helpers;

class CustomRules
{
    public static function isNotJohnDoe($data): bool
    {
        return $data !== 'John Doe';
    }
}