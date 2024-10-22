<?php

namespace Azolee\Validator;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;
use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;
use ReflectionException;

class Validator
{
    protected function __construct(
        protected ValidationErrorManager $errorManager,
        protected ValidationRuleEvaluator $ruleEvaluator
    ) {
    }

    /**
     * @throws InvalidValidationRule
     * @throws ReflectionException
     * @throws ValidationException
     */
    public static function make(
        array $validationRules,
        array $dataToValidate,
        bool $silent = true,
        ValidationErrorBagInterface $validationErrorBag = null
    ): ValidationResult {
        $errorManager = new ValidationErrorManager($validationErrorBag);
        $ruleEvaluator = new ValidationRuleEvaluator($errorManager);
        $validator = new self($errorManager, $ruleEvaluator);

        try {
            foreach ($validationRules as $field => $rules) {
                if ($validator->validateRuleTypes($rules) === false) {
                    $message = "Invalid validation rule for $field. Rule should be a string, an array or a callable.";
                    if (!$silent) {
                        throw new InvalidValidationRule($message);
                    }
                    $validator->errorManager->setFailed('invalid_rule', $field, $rules, $message);
                    continue;
                }

                $validator->ruleEvaluator->evaluate($rules, $field, $dataToValidate);

                if ($validator->errorManager->getValidationResult()->isFailed() && !$silent) {
                    throw new ValidationException($validator->errorManager->getValidationResult()->getErrorsForFailure());
                }
            }
        } catch (InvalidValidationRule|ReflectionException|ValidationException $e) {
            if (!$silent) {
                throw $e;
            }
            $validator->errorManager->setFailed('', '', $dataToValidate, $e->getMessage());
        }

        return $validator->errorManager->getValidationResult();
    }

    protected function validateRuleTypes(mixed $rules): bool
    {
        return (is_string($rules) || is_callable($rules) || is_array($rules));
    }
}