<?php

namespace Tests;

use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidateDuplicateMessage extends TestCase
{
    public function testDuplicateMessage()
    {
        $validationRules = [
            'user.name' => ['required', 'string'],
            'user.age' => 'numeric',
            'user.email' => ['required', 'email'],
            'user.website' => ['url'],
            'user.password' => ['password:duls', 'min:8'],
            'user.password_confirmation' => ['same:user.password'],
            'user.is_active' => ['boolean', 'required'],
            'address' => 'array',
            'address.city' => 'string',
            'address.street' => ['string', 'different:address.city', 'different:address.street2', 'different:address.no'],
            'address.street2' => 'not_null',
            'address.no' => 'string',
            'images' => ['array', 'min:3'],
            'images.*.url' => 'string',
            'images.*.role' => ['string', 'in:profile_photo,album_photo'],
        ];

        $dataToValidate = [
            'user' => [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => 'SecretPassword.123',
                'password_confirmation' => 'SecretPassword.123',
                'website' => 'https://github.com',
                'age' => 30,
                'is_active' => 'yes',
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
        $this->assertTrue($result->isFailed());
    }
}