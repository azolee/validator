<?php

namespace Tests;

class CustomRules
{
    public static function isNotJohnDoe($data): bool
    {
        return $data !== 'John Doe';
    }
}