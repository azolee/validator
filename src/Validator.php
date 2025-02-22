<?php

namespace Azolee\Validator;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;
use ReflectionException;

/**
 * Class Validator
 * @package Azolee\Validator
 * @method static Validator make(array $validationRules, array $dataToValidate)
 */
class Validator
{
    protected array $config = [
        'silent' => true,
        'errorBag' => null,
        'errorBagManager' => null,
        'ruleEvaluator' => null,
    ];

    protected function __construct(
        protected ValidationErrorManager $errorManager,
        protected ValidationRuleEvaluator $ruleEvaluator
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws InvalidValidationRule
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name === 'config') {
            return self::config(...$arguments);
        } elseif ($name === 'make') {
            return (new self(new ValidationErrorManager(), new ValidationRuleEvaluator(new ValidationErrorManager())))->makeOnInstance(...$arguments);
        }

        throw new \BadMethodCallException("Method $name does not exist.");
    }

    public static function config(array $config): self
    {
        $errorManager = $config['errorBagManager'] ?? new ValidationErrorManager($config['errorBag'] ?? null);
        $ruleEvaluator = $config['ruleEvaluator'] ?? new ValidationRuleEvaluator($errorManager);
        $validator = new self($errorManager, $ruleEvaluator);
        $validator->config = array_merge($validator->config, $config);
        return $validator;
    }

    /**
     * @throws InvalidValidationRule
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function makeOnInstance(
        array $validationRules,
        array $dataToValidate
    ): ValidationResult {
        $this->errorManager = $this->config['errorBagManager'] ?? new ValidationErrorManager($this->config['errorBag']);
        $this->ruleEvaluator = $this->config['ruleEvaluator'] ?? new ValidationRuleEvaluator($this->errorManager);

        return $this->validate($validationRules, $dataToValidate);
    }

    /**
     * @throws ValidationException
     * @throws ReflectionException
     * @throws InvalidValidationRule
     */
    protected function validate(
        array $validationRules,
        array $dataToValidate
    ): ValidationResult {
        try {
            foreach ($validationRules as $field => $rules) {
                if ($this->validateRuleTypes($rules) === false) {
                    $message = "Invalid validation rule for $field. Rule should be a string, an array or a callable.";
                    if (!$this->config['silent']) {
                        $this->errorManager->getValidationResult()->flushValidated();
                        throw new InvalidValidationRule($message);
                    }
                    $this->errorManager->setFailed('invalid_rule', $field, $rules, $message);
                    continue;
                }

                $this->ruleEvaluator->evaluate($rules, $field, $dataToValidate);

                if (!$this->errorManager->getValidationResult()->isFailed()) {
                    $this->errorManager->getValidationResult()->setValidated($field, $dataToValidate);
                }

                if ($this->errorManager->getValidationResult()->isFailed() && !$this->config['silent']) {
                    $this->errorManager->getValidationResult()->flushValidated();
                    throw new ValidationException($this->errorManager->getValidationResult()->getErrorsForFailure());
                }
            }
        } catch (InvalidValidationRule|ReflectionException|ValidationException $e) {
            if (!$this->config['silent']) {
                throw $e;
            }
            $this->errorManager->setFailed('', '', $dataToValidate, $e->getMessage());
        }

        return $this->errorManager->getValidationResult();
    }

    protected function validateRuleTypes(mixed $rules): bool
    {
        return (is_string($rules) || is_callable($rules) || is_array($rules));
    }

    /**
     * @throws ReflectionException
     * @throws ValidationException
     * @throws InvalidValidationRule
     */
    public function __call($name, $arguments)
    {
        if ($name === 'make') {
            return $this->makeOnInstance(...$arguments);
        }

        throw new \BadMethodCallException("Method $name does not exist.");
    }
}