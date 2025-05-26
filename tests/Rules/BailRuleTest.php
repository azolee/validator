<?php

namespace Tests\Rules;

use Azolee\Validator\ValidationErrorManager;
use Azolee\Validator\ValidationRuleEvaluator;
use PHPUnit\Framework\TestCase;

class BailRuleTest extends TestCase
{
    public function testBailRuleStopsFurtherValidation()
    {
        $dataToValidate = [
            'email' => 'invalid-email',
        ];

        $rules = ['bail', 'email', 'min:30', 'required'];

        $errorManager = new ValidationErrorManager();
        $ruleEvaluator = new ValidationRuleEvaluator($errorManager);

        $result = $ruleEvaluator->evaluate($rules, 'email', $dataToValidate);

        // Assert that validation stops after the first failure
        $this->assertNull($result);

        // Assert that only the first rule failure is recorded
        $failedRules = $errorManager->getValidationResult()->getFailedRules();

        $this->assertCount(1, $failedRules);
        $this->assertEquals('email', $failedRules[0]['rule']);
    }

    public function testBailRuleAllowsValidationWithoutFailures()
    {
        $dataToValidate = [
            'email' => 'test@example.com',
        ];

        $rules = 'bail|required|email';

        $errorManager = new ValidationErrorManager();
        $ruleEvaluator = new ValidationRuleEvaluator($errorManager);

        $result = $ruleEvaluator->evaluate($rules, 'email', $dataToValidate);

        // Assert that validation passes
        $this->assertNotNull($result);
        $this->assertContains('email', $result);

        // Assert that no rules failed
        $this->assertFalse($errorManager->getValidationResult()->isFailed());
    }
}