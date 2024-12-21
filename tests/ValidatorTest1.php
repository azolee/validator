<?php

namespace Tests;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;
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
}