<?php

namespace Azolee\Validator;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;

class ValidationErrorManager
{
    protected ValidationResult $validationResult;

    public function __construct(ValidationErrorBagInterface $validationErrorBag = null)
    {
        $this->validationResult = new ValidationResult($validationErrorBag ?? new ValidationErrorBag());
    }

    public function setFailed(string $rule, string $key, mixed $dataToValidate, ?string $message = null): void
    {
        $this->validationResult->flushValidated();
        $this->validationResult->setFailed($rule, $key, $dataToValidate, $message);
    }

    public function getValidationResult(): ValidationResult
    {
        return $this->validationResult;
    }
}