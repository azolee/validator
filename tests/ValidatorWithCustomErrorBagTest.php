<?php

namespace Tests;

use Azolee\Validator\Helpers\ErrorBags;
use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorWithCustomErrorBagTest extends TestCase
{
    public function testValidatorWithCustomErrorBag()
    {
        $validationRules = [
            'username' => 'string',
            'age' => 'numeric',
        ];
        $dataToValidate = [
            'username' => 'JohnDoe',
            'age' => 'thirty', // Invalid data
        ];

        $validationErrorBag = ErrorBags::getCustomErrorBag([
            'numeric' => 'The :attribute is not a valid number.',
        ]);

        $result = Validator::config([
            'errorBag' => $validationErrorBag,
        ])->make(
            $validationRules,
            $dataToValidate,
        );

        $this->assertTrue($result->isFailed());
        $this->assertEquals('The age is not a valid number.', $result->getErrorsForFailure());
    }

    public function testValidatorWithValidData()
    {
        $validationRules = [
            'username' => 'string',
            'age' => 'numeric',
        ];
        $dataToValidate = [
            'username' => 'JohnDoe',
            'age' => 30,
        ];

        $validationErrorBag = ErrorBags::getCustomErrorBag([
            'numeric' => 'The :attribute is not a valid number.',
        ]);

        $result = Validator::make($validationRules, $dataToValidate, true, $validationErrorBag);

        $this->assertFalse($result->isFailed());
    }
}