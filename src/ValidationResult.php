<?php

namespace Azolee\Validator;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;
use Azolee\Validator\Helpers\ClassHelper;

class ValidationResult
{

    public function __construct(
        protected ValidationErrorBagInterface $validationError = new ValidationErrorBag(),
        protected array $failedRules = []
    )
    {
    }

    /**
     * @param string $rule
     * @param string $key
     * @param mixed $dataToValidate
     * @param string|null $message
     * @return void
     */
    public function setFailed(string $rule, string $key, mixed $dataToValidate, ?string $message = null): void
    {
        $this->failedRules[] = [
                'rule' => ClassHelper::isCallable($rule) ? ValidationRules::CUSTOM_RULE : $rule,
                'key' => $key,
                'data' => $dataToValidate,
                'message' => $message ?? $this->validationError->getErrorFor($rule, $key),
            ];
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return count($this->failedRules) > 0;
    }

    /**
     * @return array
     */
    public function getFailedRules(): array
    {
        return $this->failedRules;
    }

    /**
     * @return string
     */
    public function getErrorsForFailure(): string
    {
        $errors = [];

        foreach ($this->failedRules as $failedRule) {
            $errors[] = $failedRule['message'] ?? $this->validationError->getErrorFor($failedRule['rule'], $failedRule['key']);
        }

        return join(", ", $errors);
    }
}