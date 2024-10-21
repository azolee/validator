<?php

namespace Azolee\Validator\Contracts;

interface ValidationErrorBagInterface
{
    public function getErrorFor(array|string $rules, string $attribute): string;
}