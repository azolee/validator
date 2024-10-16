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
                    $validator->lastValidationResult()->setFailed("invalid_rule", $field, $rules, $message);
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
            $rules = [$rules];
        }

        foreach ($rules as $rule) {
            if (ClassHelper::isCallable($rule)) {
                if ($this->applyCallableRule($rule, $key, $dataToValidate) === false) {
                    $this->lastValidationResult->setFailed("custom_rule", $key, $dataToValidate);
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

        if (method_exists($validationRules, $method)) {
            return $validationRules::$method($dataToValidate, $key, $additionalAttribute);
        }

        throw new InvalidValidationRule("Validaton method $method not implemented.");
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidValidationRule
     * @throws ReflectionException
     */
    private function applyCallableRule(array|callable $rule, string $key, mixed $dataToValidate): bool
    {
        $reflectionOfTheRule = ClassHelper::getReflectionForCallable($rule);
        if ($reflectionOfTheRule->getNumberOfParameters() < 1 || $reflectionOfTheRule->getNumberOfParameters() > 3) {
            throw new InvalidValidationRule("Invalid validaton closure passed for $key. Rule should have 1-3 attributes: data, key and the dataArray.");
        }

        $data = $dataToValidate;
        if ($key) {
            $data = ArrayHelper::parseNestedData($dataToValidate, $key);
        }

        return call_user_func($rule, $data, $key, $dataToValidate);
    }
}