<?php

namespace Azolee\Validator;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Helpers\ArrayHelper;
use Azolee\Validator\Helpers\ClassHelper;
use InvalidArgumentException;
use ReflectionException;

class ValidationRuleEvaluator
{
    protected ValidationErrorManager $errorManager;

    public function __construct(ValidationErrorManager $errorManager)
    {
        $this->errorManager = $errorManager;
    }

    /**
     * @throws InvalidValidationRule
     * @throws ReflectionException
     */
    public function evaluate(string|array|callable $rules, string $key, mixed $dataToValidate): void
    {
        if (!is_array($rules)) {
            $rules = (is_string($rules) && str_contains($rules, '|')) ? explode('|', $rules) : [$rules];
        }

        foreach ($rules as $rule) {
            if (ClassHelper::isCallable($rule)) {
                if ($this->applyCallableRule($rule, $key, $dataToValidate) === false) {
                    $this->errorManager->setFailed('custom_rule', $key, $dataToValidate);
                    return;
                }
                continue;
            }

            if ($this->applyRule($rule, $key, $dataToValidate) === false) {
                $this->errorManager->setFailed($rule, $key, $dataToValidate);
                return;
            }
        }
    }

    /**
     * @throws InvalidValidationRule
     */
    private function applyRule(string $rule, string $key, mixed $dataToValidate): bool
    {
        $validationRules = new ValidationRules();
        $method = $rule;
        $additionalAttribute = null;
        $extraParams = [];

        if (str_contains($rule, ':')) {
            [$method, $additionalAttribute] = explode(':', $rule, 2);
            $extraParams = explode(',', $additionalAttribute);
        }

        if (!method_exists($validationRules, $method)) {
            throw new InvalidValidationRule("Validation method $method not implemented.");
        }

        $dataSet = ArrayHelper::parseNestedData($dataToValidate, $key);

        if (empty($dataSet)) {
            return $validationRules::$method(null, $key, $additionalAttribute, $dataToValidate);
        }

        foreach ($dataSet as $data) {
            $result = $validationRules::$method($data['value'], $data['key'], $additionalAttribute, $dataToValidate);
            if ($result === false) {
                $this->errorManager->setFailed($method, $key, $dataToValidate, null, $extraParams);
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