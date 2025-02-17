<?php

namespace Tests;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;
use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\CustomRules;

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
            'address.street' => ['string', 'different:address.city', 'different:address.street2', 'different:address.no'],
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

        Validator::config(['silent' => false])->make($validationRules, $dataToValidate);
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

        Validator::config(['silent' => false])->make($validationRules, $dataToValidate);
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
            'address.street' => ['string', 'different:address.city', 'different:address.street2', 'different:address.no'],
            'address.street2' => 'not_null',
            'address.no' => 'string',
            'images.*.url' => 'string',
            'images.*.role' => function ($data) {
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
                    'description' => 'This is a photo of me.',
                ],
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());
    }

    public function testFailingNestedData()
    {
        $validationRules = [
            'user.name' => 'string',
            'user.age' => 'numeric',
            'user.is_active' => 'boolean',
            'address' => 'array',
            'address.city' => 'string',
            'address.street' => ['string', 'different:address.city', 'different:address.street2', 'different:address.no'],
            'address.street2' => 'not_null',
            'address.no' => 'string',
            'images.*.url' => 'string',
            'images.*.role' => function ($data) {
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
                    'role' => 'just_an_image', //Invalid role
                    'description' => null, //Invalid description
                ],
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
    }

    public function testAlpha()
    {
        $validationRules = [
            'username' => 'alpha',
        ];
        $dataToValidate = [
            'username' => 'JohnDoe',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['username'] = 'JohnDoe123';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testAlphaNum()
    {
        $validationRules = [
            'username' => 'alpha_num',
        ];
        $dataToValidate = [
            'username' => 'JohnDoe123',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['username'] = 'JohnDoe123!';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDigits()
    {
        $validationRules = [
            'code' => 'digits:5',
        ];
        $dataToValidate = [
            'code' => '12345',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['code'] = '1234';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDigitsBetween()
    {
        $validationRules = [
            'code' => 'digits_between:4,6',
        ];
        $dataToValidate = [
            'code' => '12345',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['code'] = '123';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $dataToValidate['code'] = '1234567';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testSame()
    {
        $validationRules = [
            'password' => 'same:password_confirmation',
        ];
        $dataToValidate = [
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());

        $dataToValidate['password_confirmation'] = 'different';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDifferent()
    {
        $validationRules = [
            'username' => 'different:email',
        ];
        $dataToValidate = [
            'username' => 'john_doe',
            'email' => 'john@example.com',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['email'] = 'john_doe';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testArray()
    {
        $validationRules = [
            'data' => 'array',
        ];
        $dataToValidate = [
            'data' => ['item1', 'item2'],
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['data'] = 'not_an_array';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testString()
    {
        $validationRules = [
            'name' => 'string',
        ];
        $dataToValidate = [
            'name' => 'John Doe',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['name'] = 12345;
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testNumeric()
    {
        $validationRules = [
            'age' => 'numeric',
        ];
        $dataToValidate = [
            'age' => 30,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['age'] = 'thirty';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testBoolean()
    {
        $validationRules = [
            'is_active' => 'boolean',
        ];
        $dataToValidate = [
            'is_active' => true,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['is_active'] = 'not_a_boolean';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testNotNull()
    {
        $validationRules = [
            'name' => 'not_null',
        ];
        $dataToValidate = [
            'name' => 'John Doe',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['name'] = null;
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testEmail()
    {
        $validationRules = [
            'email' => 'email',
        ];
        $dataToValidate = [
            'email' => 'john@example.com',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['email'] = 'invalid_email';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testUrl()
    {
        $validationRules = [
            'website' => 'url',
        ];
        $dataToValidate = [
            'website' => 'https://example.com',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['website'] = 'invalid_url';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testMin()
    {
        $validationRules = [
            'age' => 'min:18',
        ];
        $dataToValidate = [
            'age' => 20,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['age'] = 17;
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $validationRules = [
            'password' => 'min:10',
        ];
        $dataToValidate = [
            'password' => 'maxuserpassword',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());


        $validationRules = [
            'images' => 'min:3',
        ];
        $dataToValidate = [
            'images' => ['image1.jpg', 'image2.jpg'],
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $dataToValidate = [
            'images' => true,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testMax()
    {
        $validationRules = [
            'age' => 'max:65',
        ];
        $dataToValidate = [
            'age' => 60,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['age'] = 70;
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $validationRules = [
            'password' => 'max:20',
        ];
        $dataToValidate = [
            'password' => 'maxuserpassword',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());


        $validationRules = [
            'images' => 'max:2',
        ];
        $dataToValidate = [
            'images' => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $dataToValidate = [
            'images' => true,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testIn()
    {
        $validationRules = [
            'role' => 'in:admin,user,guest',
        ];
        $dataToValidate = [
            'role' => 'admin',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['role'] = 'superadmin';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDate()
    {
        $validationRules = [
            'birthday' => 'date',
        ];
        $dataToValidate = [
            'birthday' => '1990-01-01',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['birthday'] = 'invalid_date';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testIp()
    {
        $validationRules = [
            'ip_address' => 'ip',
        ];
        $dataToValidate = [
            'ip_address' => '192.168.1.1',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['ip_address'] = 'invalid_ip';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testJson()
    {
        $validationRules = [
            'json_data' => 'json',
        ];
        $dataToValidate = [
            'json_data' => '{"name": "John", "age": 30}',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['json_data'] = 'invalid_json';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testRegex()
    {
        $validationRules = [
            'username' => 'regex:/^[a-zA-Z0-9_]+$/',
        ];
        $dataToValidate = [
            'username' => 'valid_username123',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['username'] = 'invalid-username!';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testRequired()
    {
        $validationRules = [
            'name' => 'required',
        ];
        $dataToValidate = [
            'name' => 'John Doe',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['name'] = '';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testAlphaDash()
    {
        $validationRules = [
            'username' => 'alpha_dash',
        ];
        $dataToValidate = [
            'username' => 'john_doe-123',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['username'] = 'john@doe';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testAfter()
    {
        $validationRules = [
            'start_date' => 'after:2023-01-01',
        ];
        $dataToValidate = [
            'start_date' => '2023-01-02',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['start_date'] = '2022-12-31';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testBefore()
    {
        $validationRules = [
            'end_date' => 'before:2023-01-01',
        ];
        $dataToValidate = [
            'end_date' => '2022-12-31',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['end_date'] = '2023-01-02';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testActiveUrl()
    {
        $validationRules = [
            'website' => 'active_url',
        ];
        $dataToValidate = [
            'website' => 'https://www.google.com',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['website'] = 'https://khgvncabcasbqiuw.com';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testAscii()
    {
        $validationRules = [
            'text' => 'ascii',
        ];
        $dataToValidate = [
            'text' => 'Hello World!',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['text'] = 'こんにちは世界';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDateEquals()
    {
        $validationRules = [
            'event_date' => 'date_equals:2023-01-01',
        ];
        $dataToValidate = [
            'event_date' => '2023-01-01',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['event_date'] = '2023-01-02';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDistinct()
    {
        $validationRules = [
            'items.*.id' => 'distinct',
        ];
        $dataToValidate = [
            'items' => [
                ['id' => 1],
                ['id' => 2],
                ['id' => 3],
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['items'][2]['id'] = 2;
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDistinctStrict()
    {
        $validationRules = [
            'items.*.id' => 'distinct:strict',
        ];
        $dataToValidate = [
            'items' => [
                ['id' => 1],
                ['id' => '1'],
                ['id' => 2],
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $dataToValidate['items'][1]['id'] = 3;
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());
    }

    public function testDistinctIgnoreCase()
    {
        $validationRules = [
            'items.*.name' => 'distinct:ignore_case',
        ];
        $dataToValidate = [
            'items' => [
                ['name' => 'Item1'],
                ['name' => 'item1'],
                ['name' => 'Item2'],
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $dataToValidate['items'][1]['name'] = 'Item3';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());
    }

    public function testMakeWithClassMethodRule()
    {
        $validationRules = [
            'user.name' => [[CustomRules::class, 'isNotJohnDoe'], 'string'],
        ];

        $dataToValidate = [
            'user' => [
                'name' => 'John Smith'
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());

        $dataToValidate['user']['name'] = 'John Doe';
        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
    }

    public function testMakeWithObjectMethodRule()
    {
        $customRules = new CustomRules();
        $validationRules = [
            'user.name' => [[$customRules, 'isNotJohnDoe'], 'string'],
        ];

        $dataToValidate = [
            'user' => [
                'name' => 'John Smith'
            ],
        ];

        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertFalse($result->isFailed());

        $dataToValidate['user']['name'] = 'John Doe';
        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
    }

    public function testMultipleRulesSeparatedByPipes()
    {
        $validationRules = [
            'username' => 'required|string|alpha_num',
        ];
        $dataToValidate = [
            'username' => 'johndoe123',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['username'] = '';
        $result = Validator::make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
    }

    public function testPasswordRule()
    {
        $validationRules = [
            'password' => ['password:ulds', 'min:8'],
        ];
        $dataToValidate = [
            'password' => 'StrongPass1!',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $validationRules['password'] = 'password';
        $dataToValidate['password'] = 'weekpassword';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $validationRules['password'] = 'password:ulds';
        $dataToValidate['password'] = 'weekpassword';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $validationRules['password'] = 'password:ulds';
        $dataToValidate['password'] = 'weekpassword';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $validationRules['password'] = ['password:ulds', 'min:12'];
        $dataToValidate['password'] = 'ShortPass1!';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());

        $validationRules['password'] = ['password:ulds', 'min:11'];
        $dataToValidate['password'] = 'Short.Pass1';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());
    }

    public function testContains()
    {
        $validationRules = [
            'description' => 'contains:example',
        ];
        $dataToValidate = [
            'description' => 'This is an example description.',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['description'] = 'This is a test description.';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testDateFormat()
    {
        $validationRules = [
            'event_date' => 'date_format:Y-m-d',
        ];
        $dataToValidate = [
            'event_date' => '2023-01-01',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['event_date'] = '01-01-2023';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testSetFailedOnInvalidRuleType()
    {
        $validationRules = [
            'field' => 123, // Invalid rule type
        ];
        $dataToValidate = [
            'field' => 'value',
        ];

        $validator = Validator::config(['silent' => true]);
        $result = $validator->make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
        $this->assertEquals('invalid_rule', $result->getFailedRules()[0]['rule']);
    }

    public function testSetFailedOnException()
    {
        $validationRules = [
            'field' => 'invalid_rule', // This should trigger an exception
        ];
        $dataToValidate = [
            'field' => 'value',
        ];

        $validator = Validator::config(['silent' => true]);
        $result = $validator->make($validationRules, $dataToValidate);

        $this->assertTrue($result->isFailed());
        $this->assertEquals('', $result->getFailedRules()[0]['rule']);
        $this->assertEquals('', $result->getFailedRules()[0]['key']);
        $this->assertEquals($dataToValidate, $result->getFailedRules()[0]['data']);
        $this->assertNotEmpty($result->getFailedRules()[0]['message']);
    }

    public function testBadMethodCallException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Method nonExistentMethod does not exist.');

        Validator::nonExistentMethod();
    }

    public function testValidSmallBase64String()
    {
        $validationRules = [
            'data' => 'base64',
        ];
        $dataToValidate = [
            'data' => base64_encode('small string'),
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());
    }

    public function testInvalidSmallBase64String()
    {
        $validationRules = [
            'data' => 'base64',
        ];
        $dataToValidate = [
            'data' => 'invalid_base64_string',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testValidHugeBase64String()
    {
        $validationRules = [
            'data' => 'base64',
        ];
        $dataToValidate = [
            'data' => base64_encode(str_repeat('Abc', 333333) . "d"), // 1MB string
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());
    }

    public function testInvalidHugeBase64String()
    {
        $validationRules = [
            'data' => 'base64',
        ];
        $dataToValidate = [
            'data' => str_repeat('A-', 500000), // 1MB invalid base64 string
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testValidBase64EncodedImage()
    {
        $validationRules = [
            'image' => 'base64',
        ];
        $imagePath = __DIR__ . '/images/valid_image.jpg';
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);

        $dataToValidate = [
            'image' => $base64Image,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());
    }

    public function testInvalidBase64EncodedImage()
    {
        $validationRules = [
            'image' => 'base64',
        ];
        $invalidBase64Image = 'invalid_base64_string';

        $dataToValidate = [
            'image' => $invalidBase64Image,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testBase64WithChunkSize()
    {
        $validationRules = [
            'data' => 'base64:4096', // Specify chunk size of 4096 bytes
        ];
        $imagePath = __DIR__ . '/images/valid_image.jpg';
        $imageData = file_get_contents($imagePath);
        $validBase64String = base64_encode($imageData);

        $dataToValidate = [
            'data' => $validBase64String,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $validationRules = [
            'data' => 'base64:chunk_size:4096', // Specify chunk size of 4096 bytes
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['data'] = $validBase64String."_";
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testValidBase64Image()
    {
        $validationRules = [
            'image' => 'base64_image',
        ];
        $imagePath = __DIR__ . '/images/valid_image.jpg';
        $imageData = file_get_contents($imagePath);
        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageData);

        $dataToValidate = [
            'image' => $base64Image,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());
    }

    public function testInvalidBase64Image()
    {
        $validationRules = [
            'image' => 'base64_image',
        ];
        $invalidBase64Image = 'data:image/jpeg;base64,invalid_base64_string';

        $dataToValidate = [
            'image' => $invalidBase64Image,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testMissingDataUrlScheme()
    {
        $validationRules = [
            'image' => 'base64_image',
        ];
        $imagePath = __DIR__ . '/images/valid_image.jpg';
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData); // Missing data URL scheme

        $dataToValidate = [
            'image' => $base64Image,
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testValidatorWithUuidRule()
    {
        $validationRules = [
            'uuid-field' => 'uuid',
        ];
        $dataToValidate = [
            'uuid-field' => '123e4567-e89b-12d3-a456-426614174000',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['uuid-field'] = 'invalid-uuid';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The uuid-field is not a valid UUID.', $result->getFailedRules()[0]['message']);
    }
}