<?php

namespace Tests;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorExamplesTest extends TestCase
{
    public function testBasicUsage()
    {
        $validationRules = [
            'user.name' => 'string',
            'user.age' => 'numeric',
            'user.email' => ['email', 'not_null'],
            'user.website' => ['url'],
            'user.password' => ['password:ulds', 'min:8'],
            'user.password_confirmation' => ['same:user.password'],
            'user.is_active' => ['boolean', 'not_null'],
            'address' => 'array',
            'address.city' => 'string',
            'address.street' => ['string', 'different:address.city', 'different:address.street2', 'different:address.no'],
            'address.street2' => 'not_null',
            'address.no' => 'string',
            'images.*.url' => 'string',
            'images.*.role' => ['string', 'in:profile_photo,album_photo'],
        ];

        $dataToValidate = [
            'user' => [
                'name' => 'John Doe',
                'email' => 'user@email.com',
                'password' => 'Secret.123',
                'password_confirmation' => 'Secret.123',
                'website' => 'https://github.com',
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
                ],
                [
                    'url' => 'image2.jpg',
                    'role' => 'album_photo',
                    'description' => 'This is a photo of me.',
                ],
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed(), "Validation should pass.");

        $dataToValidate['user']['password'] = 'secret';
        $dataToValidate['user']['password_confirmation'] = 'secret';
        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed(), "Validation should pass.");
    }

    public function testCustomRules()
    {
        $validationRules = [
            'user.name' => [
                function ($data) {
                    return $data !== 'John Doe';
                },
                'string',
            ],
        ];

        $dataToValidate = [
            'user' => [
                'name' => 'John Smith'
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed(), "Validation should pass.");
    }

    public function testExceptionHandling()
    {
        $this->expectException(InvalidValidationRule::class);

        $validationRules = [
            'name' => 123, // Invalid rule
        ];
        $dataToValidate = [
            'name' => 'John Doe',
        ];

        Validator::config(['silent' => false])->make($validationRules, $dataToValidate);
    }

    public function testValidatorWithCharsetRule()
    {
        $validationRules = [
            'note' => 'charset:UTF-8',
        ];
        $dataToValidate = [
            'note' => 'Valid UTF-8 string',
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());

        $dataToValidate['note'] = "\xC3\x28"; // Invalid UTF-8 string
        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
        $errors = $result->getFailedRules();
        $this->assertEquals(
                'The note is not valid UTF-8 charset.',
                $errors[0]['message']
            );
    }
}