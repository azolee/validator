<?php

namespace Tests\Rules;

use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatePresent extends TestCase
{
    public function testPresentRuleForFlatData()
    {
        $validationRules = [
            'username' => 'present',
        ];
        $dataToValidate = [
            'username' => 'JohnDoe',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        unset($dataToValidate['username']);
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testPresentRuleForNestedData()
    {
        $validationRules = [
            'user.name' => 'present',
        ];
        $dataToValidate = [
            'user' => [
                'name' => 'John Doe',
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        unset($dataToValidate['user']['name']);
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }
}