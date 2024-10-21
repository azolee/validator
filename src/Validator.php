<?php

namespace Azolee\Validator;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;
use Azolee\Validator\Helpers\ArrayHelper;
use Azolee\Validator\Helpers\ClassHelper;
use Exception;
use InvalidArgumentException;
use ReflectionException;

class Validator
{
    protected function __construct(
        protected ValidationResult $lastValidationResult = new ValidationResult()
    ) {
    }

    /**
     * @throws InvalidValidationRule
     * @throws ReflectionException
     * @throws ValidationException
     */
    public static function make(array $validationRules, array $dataToValidate, bool $silent = true): ValidationResult
    {
        $validator = new static();
        try {
            foreach ($validationRules as $field => $rules) {
                if ($validator->validateRuleTypes($rules) === false) {
                    $message = "Invalid validation rule for $field. Rule should be a string, an array or a callable.";
                    if (!$silent) {
                        throw new InvalidValidationRule($message);
                    }
                    $validator->lastValidationResult()->setFailed('invalid_rule', $field, $rules, $message);
                    continue;
                }

                $validator->evaluate($rules, $field, $dataToValidate);

                if ($validator->failed() && !$silent) {
                    throw new ValidationException($validator->lastValidationResult()->getErrorsForFailure());
                }
            }
        } catch (Exception $e) {
            if (!$silent) {
                throw $e;
            }
            $validator->lastValidationResult()->setFailed('', '', $dataToValidate, $e->getMessage());
        }

        return $validator->lastValidationResult();
    }

    public function failed(): bool
    {
        return $this->lastValidationResult->isFailed();
    }

    public function lastValidationResult(): ValidationResult
    {
        return $this->lastValidationResult;
    }

    /**
     * @throws InvalidValidationRule
     * @throws ReflectionException
     */
    protected function evaluate(string|array|callable $rules, string $key, mixed $dataToValidate): void
    {
        if (!is_array($rules)) {
            $rules = (is_string($rules) && str_contains($rules, '|')) ? explode('|', $rules) : [$rules];
        }

        foreach ($rules as $rule) {
            if (ClassHelper::isCallable($rule)) {
                if ($this->applyCallableRule($rule, $key, $dataToValidate) === false) {
                    $this->lastValidationResult->setFailed('custom_rule', $key, $dataToValidate);
                    return;
                }
                continue;
            }

            if ($this->applyRule($rule, $key, $dataToValidate) === false) {
                $this->lastValidationResult->setFailed($rule, $key, $dataToValidate);
                return;
            }
        }
    }

    protected function validateRuleTypes(mixed $rules): bool
    {
        return (is_string($rules) || is_callable($rules) || is_array($rules));
    }

    /**
     * @throws InvalidValidationRule
     */
    private function applyRule(string $rule, string $key, mixed $dataToValidate): bool
    {
        $validationRules = new ValidationRules();
        $method = $rule;
        $additionalAttribute = null;

        if (str_contains($rule, ':')) {
            [$method, $additionalAttribute] = explode(':', $rule, 2);
        }

        if (!method_exists($validationRules, $method)) {
            throw new InvalidValidationRule("Validaton method $method not implemented.");
        }

        $dataSet = ArrayHelper::parseNestedData($dataToValidate, $key);

        foreach ($dataSet as $data) {
            $result = $validationRules::$method($data['value'], $data['key'], $additionalAttribute, $dataToValidate);
            if ($result === false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidValidationRule
     * @throws ReflectionException
     */
    private function applyCallableRule(array|callable $rule, string $key, mixed $dataToValidate): bool
    {
        ClassHelper::validateCallable($rule, "Invalid closure passed for $key: should have 1-3 attributes: data, key and the dataArray.");

        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key) ?? [];
        }

        $handleItem = function ($item) use ($rule, $key, $dataToValidate) {
            if (array_key_exists('value', $item) === false || array_key_exists('key', $item) === false) {
                throw new InvalidArgumentException("Invalid data structure for $key.");
            }
            $result = call_user_func($rule, ($item['value'] ?? null), ($item['key'] ?? $key), $dataToValidate);
            if (gettype($result) !== 'boolean') {
                throw new InvalidValidationRule("Validation rule for $key should return a boolean.");
            }
            return $result;
        };

        if (!array_is_list($data)) {
            return $handleItem($data);
        }

        foreach ($data as $item) {
            if ($handleItem($item) === false) {
                return false;
            }
        }
        return true;
    }
}