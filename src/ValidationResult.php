<?php

namespace Azolee\Validator;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;
use Azolee\Validator\Helpers\ArrayHelper;
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
     * @param array $extraParams
     * @return void
     */
    public function setFailed(string $rule, string $key, mixed $dataToValidate, ?string $message = null, array $extraParams = []): void
    {
        if (ClassHelper::isCallable($rule)) {
            $rule = ValidationRules::CUSTOM_RULE;
        }
        if(str_contains($rule, ':')) {
            $ruleParts = explode(':', $rule, 2);
            $rule = array_shift($ruleParts);
            $values = ArrayHelper::prependStringToNumericKeys(
                    ArrayHelper::transformStringToArray(join(',', $ruleParts)),
                    'value'
                );
            $extraParams = array_merge($values, $extraParams);
        }

        $failure = [
            'rule' => $rule,
            'key' => $key,
            'data' => $dataToValidate,
            'message' => $message ?? $this->validationError->getErrorFor($rule, $key, $extraParams),
            'extraParams' => $extraParams,
        ];

        $failedRules = array_column($this->failedRules, 'rule');
        if (!in_array($failure['rule'], $failedRules)) {
            $this->failedRules[] = $failure;
        }
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