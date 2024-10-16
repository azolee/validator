<?php

namespace Azolee\Validator;

class ValidationResult
{
    protected array $failedRules = [];

    /**
     * @param string $rule
     * @param string $key
     * @param mixed $dataToValidate
     * @param string|null $message
     * @return void
     */
    public function setFailed(string $rule, string $key, mixed $dataToValidate, ?string $message = null): void
    {
        $validationError = new ValidationError();
        $this->failedRules[] = [
                'rule' => is_callable($rule) ? 'custom_rule' : $rule,
                'key' => $key,
                'data' => $dataToValidate,
                'message' => $message ?? $validationError->getErrorFor($rule, $key),
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
        $validationError = new ValidationError();
        $errors = [];

        foreach ($this->failedRules as $failedRule) {
            $errors[] = $failedRule['message'] ?? $validationError->getErrorFor($failedRule['rule'], $failedRule['key']);
        }

        return join(", ", $errors);
    }
}