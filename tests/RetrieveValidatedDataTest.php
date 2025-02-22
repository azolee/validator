<?php

namespace Tests;

use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class RetrieveValidatedDataTest extends TestCase
{
    public function testValidationSuccess()
    {
        $dataToValidate = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ];

        $validationRules = [
            'name' => 'required|string',
            'email' => 'required|email',
        ];

        $validator = Validator::make($validationRules, $dataToValidate);
        $result = $validator->validated();

        $this->assertFalse($validator->isFailed());
        $this->assertEquals($dataToValidate, $result);
    }

    public function testValidationSuccessOnMultilevelArray()
    {
        $dataToValidate = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'address' => [
                'street' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
            ],
        ];

        $validationRules = [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'address.street' => ['required', 'string'],
            'address.city' => ['required', 'string'],
            'address.state' => ['required', 'string'],
            'address.zip' => ['required', 'string'],
        ];

        $validator = Validator::make($validationRules, $dataToValidate);
        $result = $validator->validated();
        $this->assertFalse($validator->isFailed());
        $this->assertEquals($dataToValidate, $result);
    }

    public function testValidationFailure()
    {
        $dataToValidate = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
        ];

        $validationRules = [
            'name' => 'required|string',
            'email' => 'required|email',
        ];

        $validator = Validator::make($validationRules, $dataToValidate);
        $result = $validator->validated();

        $this->assertTrue($validator->isFailed());
        $this->assertArrayNotHasKey('email', $result);
        $this->assertArrayNotHasKey('name', $result);
    }

    public function testPartialValidation()
    {
        $dataToValidate = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'age' => 30,
        ];

        $validationRules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'age' => 'required|integer',
        ];

        $validator = Validator::make($validationRules, $dataToValidate);
        $result = $validator->validated();

        $this->assertTrue($validator->isFailed());
        $this->assertArrayNotHasKey('name', $result);
        $this->assertArrayNotHasKey('email', $result);
        $this->assertArrayNotHasKey('age', $result);
    }
}