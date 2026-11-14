<?php

namespace Tests;

use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class MoneyValidationRulesTest extends TestCase
{
    public function testDecimalRule()
    {
        $this->assertFalse(Validator::make(['rate' => 'decimal:2'], ['rate' => '19.99'])->isFailed());
        $this->assertFalse(Validator::make(['rate' => 'decimal:2'], ['rate' => 10])->isFailed());
        $this->assertTrue(Validator::make(['rate' => 'decimal:2'], ['rate' => '19.999'])->isFailed());
        $this->assertEquals(
            'The rate must be a valid decimal number.',
            Validator::make(['rate' => 'decimal:2'], ['rate' => '19.999'])->getFailedRules()[0]['message']
        );
    }

    public function testMoneyRule()
    {
        $this->assertFalse(Validator::make(['price' => 'money:2'], ['price' => '0'])->isFailed());
        $this->assertFalse(Validator::make(['price' => 'money:2'], ['price' => '19.99'])->isFailed());
        $this->assertTrue(Validator::make(['price' => 'money:2'], ['price' => '-1'])->isFailed());
        $this->assertTrue(Validator::make(['price' => 'money:2'], ['price' => '1.234'])->isFailed());
        $this->assertEquals(
            'The price must be a valid money amount.',
            Validator::make(['price' => 'money'], ['price' => '-5'])->getFailedRules()[0]['message']
        );
    }

    public function testPositiveRule()
    {
        $this->assertFalse(Validator::make(['qty' => 'positive'], ['qty' => 0.01])->isFailed());
        $this->assertTrue(Validator::make(['qty' => 'positive'], ['qty' => 0])->isFailed());
        $this->assertTrue(Validator::make(['qty' => 'positive'], ['qty' => -1])->isFailed());
    }

    public function testCurrencyRule()
    {
        $this->assertFalse(Validator::make(['code' => 'currency'], ['code' => 'eur'])->isFailed());
        $this->assertFalse(Validator::make(['code' => 'currency'], ['code' => 'HUF'])->isFailed());
        $this->assertTrue(Validator::make(['code' => 'currency'], ['code' => 'EURO'])->isFailed());
        $this->assertEquals(
            'The code must be a valid currency code.',
            Validator::make(['code' => 'currency'], ['code' => 'XXX1'])->getFailedRules()[0]['message']
        );
    }

    public function testAmountBetweenRule()
    {
        $this->assertFalse(Validator::make(['total' => 'amount_between:0.01,9999.99'], ['total' => '120.50'])->isFailed());
        $this->assertTrue(Validator::make(['total' => 'amount_between:0.01,9999.99'], ['total' => '0'])->isFailed());
        $this->assertEquals(
            'The total must be between 0.01 and 9999.99.',
            Validator::make(['total' => 'amount_between:0.01,9999.99'], ['total' => '10000'])->getFailedRules()[0]['message']
        );
    }

    public function testCreditCardRule()
    {
        $this->assertFalse(Validator::make(['card' => 'credit_card'], ['card' => '4111111111111111'])->isFailed());
        $this->assertFalse(Validator::make(['card' => 'credit_card'], ['card' => '4111-1111-1111-1111'])->isFailed());
        $this->assertTrue(Validator::make(['card' => 'credit_card'], ['card' => '4111111111111112'])->isFailed());
    }

    public function testCardBrandRule()
    {
        $this->assertFalse(Validator::make(['card' => 'card_brand:visa,mastercard'], ['card' => '4111111111111111'])->isFailed());
        $this->assertFalse(Validator::make(['card' => 'card_brand:mastercard'], ['card' => '5555555555554444'])->isFailed());
        $this->assertTrue(Validator::make(['card' => 'card_brand:visa,mastercard'], ['card' => '378282246310005'])->isFailed());
    }

    public function testCvvRule()
    {
        $this->assertFalse(Validator::make(['cvv' => 'cvv'], ['cvv' => '123'])->isFailed());
        $this->assertFalse(Validator::make(['cvv' => 'cvv:4'], ['cvv' => '1234'])->isFailed());
        $this->assertTrue(Validator::make(['cvv' => 'cvv:3'], ['cvv' => '1234'])->isFailed());
        $this->assertTrue(Validator::make(['cvv' => 'cvv'], ['cvv' => '12a'])->isFailed());
    }

    public function testExpiryDateRule()
    {
        $future = (new \DateTimeImmutable('first day of +1 year'))->format('m/y');
        $this->assertFalse(Validator::make(['expires' => 'expiry_date'], ['expires' => $future])->isFailed());
        $this->assertTrue(Validator::make(['expires' => 'expiry_date'], ['expires' => '01/20'])->isFailed());
        $this->assertTrue(Validator::make(['expires' => 'expiry_date'], ['expires' => '13/30'])->isFailed());
    }

    public function testAccountNumberRule()
    {
        $this->assertFalse(Validator::make(['account' => 'account_number:8,17'], ['account' => '12345678'])->isFailed());
        $this->assertFalse(Validator::make(['account' => 'account_number'], ['account' => '1234-5678'])->isFailed());
        $this->assertTrue(Validator::make(['account' => 'account_number:8,17'], ['account' => '123'])->isFailed());
    }

    public function testVatRule()
    {
        $this->assertFalse(Validator::make(['tax' => 'vat:EU'], ['tax' => 'DE123456789'])->isFailed());
        $this->assertFalse(Validator::make(['tax' => 'vat:RO'], ['tax' => 'RO12345678'])->isFailed());
        $this->assertTrue(Validator::make(['tax' => 'vat:EU'], ['tax' => 'INVALID'])->isFailed());
        $this->assertEquals(
            'The tax must be a valid VAT number.',
            Validator::make(['tax' => 'vat'], ['tax' => '!!'])->getFailedRules()[0]['message']
        );
    }

    public function testPercentageRule()
    {
        $this->assertFalse(Validator::make(['discount' => 'percentage:2'], ['discount' => '12.50'])->isFailed());
        $this->assertFalse(Validator::make(['discount' => 'percentage'], ['discount' => 0])->isFailed());
        $this->assertFalse(Validator::make(['discount' => 'percentage'], ['discount' => 100])->isFailed());
        $this->assertTrue(Validator::make(['discount' => 'percentage'], ['discount' => 150])->isFailed());
        $this->assertTrue(Validator::make(['discount' => 'percentage:2'], ['discount' => '12.555'])->isFailed());
    }

    public function testMinorUnitsRule()
    {
        $this->assertFalse(Validator::make(['cents' => 'minor_units'], ['cents' => 1999])->isFailed());
        $this->assertFalse(Validator::make(['cents' => 'minor_units'], ['cents' => '0'])->isFailed());
        $this->assertTrue(Validator::make(['cents' => 'minor_units'], ['cents' => 19.99])->isFailed());
        $this->assertTrue(Validator::make(['cents' => 'minor_units'], ['cents' => -1])->isFailed());
        $this->assertEquals(
            'The cents must be a non-negative integer in minor units.',
            Validator::make(['cents' => 'minor_units'], ['cents' => -5])->getFailedRules()[0]['message']
        );
    }
}
