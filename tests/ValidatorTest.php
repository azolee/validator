<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;
use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testMakeWithValidRules()
    {
        $validationRules = [
            'user.name' => 'string',
            'user.age' => 'numeric',
            'user.is_active' => 'boolean',
            'address' => 'array',
            'address.city' => 'string',
            'address.street' => ['string', 'not_equals_field:address.city', 'not_equals_field:address.street2', 'not_equals_field:address.no'],
            'address.street2' => 'not_null',
            'address.no' => 'string',
        ];
        $dataToValidate = [
            'user' => [
                'name' => 'John Doe',
                'age' => 30,
                'is_active' => true,
            ],
            'address' => [
                'city' => 'New York',
                'street' => 'First Avenue',
                'street2' => '',
                'no' => '52A',
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());
    }

    public function testMakeWithInvalidRuleType()
    {
        $this->expectException(InvalidValidationRule::class);

        $validationRules = [
            'name' => 123, // Invalid rule
        ];
        $dataToValidate = [
            'name' => 'John Doe',
        ];

        Validator::make($validationRules, $dataToValidate, false);
    }

    public function testMakeWithInvalidData()
    {
        $this->expectException(ValidationException::class);

        $validationRules = [
            'age' => 'numeric',
        ];
        $dataToValidate = [
            'age' => 'thirty', // Invalid data
        ];

        Validator::make($validationRules, $dataToValidate, false);
    }

    public function testMakeWithSilentMode()
    {
        $validationRules = [
            'name' => 'string',
            'age' => 'numeric',
        ];
        $dataToValidate = [
            'name' => 'John Doe',
            'age' => 'thirty', // Invalid data
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
        $this->assertCount(1, $result->getFailedRules());

        $dataToValidate['age'] = 30;
        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());
        $this->assertCount(0, $result->getFailedRules());
    }

    public function testMakeWithCustomRule()
    {
        $validationRules = [
            'user.name' => [
                function ($data) {
                    return $data === 'John Doe';
                },
                'string',
            ],
        ];

        $dataToValidate = [
            'user' => [
                'name' => 'John Doe'
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());
    }

    public function testNestedData()
    {

        $validationRules = [
            'user.name' => 'string',
            'user.age' => 'numeric',
            'user.is_active' => 'boolean',
            'address' => 'array',
            'address.city' => 'string',
            'address.street' => ['string', 'not_equals_field:address.city', 'not_equals_field:address.street2', 'not_equals_field:address.no'],
            'address.street2' => 'not_null',
            'address.no' => 'string',
            'images.*.url' => 'string',
            'images.*.role' => function($data) {
                return in_array($data, ['profile_photo', 'album_photo']);
            },
            'images.*.description' => 'string',
        ];

        $dataToValidate = [
            'user' => [
                'name' => 'John Doe',
                'age' => 30,
                'is_active' => true,
            ],
            'address' => [
                'city' => 'New York',
                'street' => 'First Avenue',
                'street2' => '',
                'no' => '52A',
            ],
            'images' => [
                [
                    'url' => 'image1.jpg',
                    'role' => 'profile_photo',
                    'description' => 'This is me this year.',
                ],
                [
                    'url' => 'image2.jpg',
                    'role' => 'album_photo',
                    'description' => 'This is a photo of me in the mountains.',
                ],
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());
    }
}