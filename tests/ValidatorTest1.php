<?php

namespace Tests;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;
use Azolee\Validator\ValidationRules;
use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\CustomRules;

class ValidatorTest1 extends TestCase
{
    public function testValidatorWithSlugRule()
    {
        $validationRules = [
            'slug-field' => 'slug',
        ];
        $dataToValidate = [
            'slug-field' => 'valid-slug-123',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['slug-field'] = 'Invalid Slug!';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The slug-field is not a valid slug.', $result->getFailedRules()[0]['message']);
    }

    public function testValidatorWithIbanRule()
    {
        $validationRules = [
            'iban-no' => 'iban',
        ];
        $dataToValidate = [
            'iban-no' => 'GB82WEST12345698765432',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['iban-no'] = 'invalid-iban';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The iban-no is not a valid IBAN.', $result->getFailedRules()[0]['message']);
    }

    public function testValidatorWithHexColorRule()
    {
        $validationRules = [
            'color' => 'hex_color',
        ];
        $dataToValidate = [
            'color' => '#aabbcc',
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertFalse($result->isFailed());

        $dataToValidate['color'] = 'invalid-color';
        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The color is not a valid hex color.', $result->getFailedRules()[0]['message']);
    }

    public function testTimezoneValidation()
    {
        $data = [
            'user_timezone' => 'America/New_York',
        ];

        $rules = [
            'user_timezone' => 'timezone',
        ];

        $result = Validator::make($rules, $data);

        $this->assertFalse($result->isFailed());

        $data = [
            'user_timezone' => 'Invalid/Timezone',
        ];

        $result = Validator::make($rules, $data);

        $this->assertTrue($result->isFailed());
    }
    public function testMinWords()
    {
        $data = [
            'description' => 'This is a test',
        ];

        $rules = [
            'description' => 'min_words:3',
        ];

        $result = Validator::make($rules, $data);
        $this->assertFalse($result->isFailed());

        $data = [
            'description' => 'Test',
        ];

        $result = Validator::make($rules, $data);
        $this->assertTrue($result->isFailed());
    }

    public function testMaxWords()
    {
        $data = [
            'description' => 'This is a simple test',
        ];

        $rules = [
            'description' => 'max_words:5',
        ];

        $result = Validator::make($rules, $data);
        $this->assertFalse($result->isFailed());

        $data = [
            'description' => 'This is a very simple test with too many words',
        ];

        $result = Validator::make($rules, $data);
        $this->assertTrue($result->isFailed());
    }
}