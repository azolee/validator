<?php

namespace Azolee\Validator\Contracts;

interface CustomRule
{
    /**
     * Validate the given value.
     *
     * @param mixed $value The value to validate.
     * @param string $key The key of the field being validated.
     * @param mixed $dataToValidate The entire data set being validated.
     * @return bool True if validation passes, false otherwise.
     */
    public function validate(mixed $value, string $key, mixed $dataToValidate): bool;

    /**
     * Get the error message for the rule.
     *
     * @return string The error message.
     */
    public function message(): string;
}