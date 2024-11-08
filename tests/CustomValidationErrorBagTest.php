<?php

namespace Tests;

use Azolee\Validator\Helpers\ErrorBags;
use PHPUnit\Framework\TestCase;

class CustomValidationErrorBagTest extends TestCase
{
    public function testGetErrorForSingleRule()
    {
        $errorBag = ErrorBags::getCustomErrorBag([
            'numeric' => 'The :attribute is not a valid number.',
        ]);

        $error = $errorBag->getErrorFor('numeric', 'age');
        $this->assertEquals('The age is not a valid number.', $error);
    }

    public function testGetErrorForMultipleRules()
    {
        $errorBag = ErrorBags::getCustomErrorBag([
            'numeric' => 'The :attribute is not a valid number.',
            'string' => 'The :attribute must be a string.',
        ]);

        $error = $errorBag->getErrorFor(['numeric', 'string'], 'age');
        $this->assertEquals('The age is not a valid number., The age must be a string.', $error);
    }

    public function testGetErrorForCustomRule()
    {
        $errorBag = ErrorBags::getCustomErrorBag([
            'custom_rule' => 'The :attribute is invalid.',
        ]);

        $error = $errorBag->getErrorFor('custom_rule', 'username');
        $this->assertEquals('The username is invalid.', $error);
    }

    public function testGetErrorForRuleWithAdditionalAttribute()
    {
        $errorBag = ErrorBags::getCustomErrorBag([
            'not_equals_field' => 'The :attribute does not equal :value.',
        ]);

        $error = $errorBag->getErrorFor('not_equals_field:password', 'confirm_password');
        $this->assertEquals('The confirm_password does not equal password.', $error);
    }

    public function testGetErrorForUnknownRule()
    {
        $errorBag = ErrorBags::getCustomErrorBag();

        $error = $errorBag->getErrorFor('unknown_rule', 'field');
        $this->assertEquals('The field is invalid.', $error);
    }
}