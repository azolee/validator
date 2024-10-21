<?php

namespace Tests\Helpers;

class CustomRules
{
    public static function isNotJohnDoe($data): bool
    {
        return $data !== 'John Doe';
    }
}