<?php

namespace Tests;

use Azolee\Validator\Validator;
use PHPUnit\Framework\TestCase;

class UniqueValidationRulesTest extends TestCase
{
    public function testBicRule()
    {
        $result = Validator::make(['code' => 'bic'], ['code' => 'DEUTDEFF']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['code' => 'bic'], ['code' => 'DEUTDEFF500']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['code' => 'bic'], ['code' => 'INVALID']);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The code must be a valid BIC/SWIFT code.', $result->getFailedRules()[0]['message']);
    }

    public function testCidrRule()
    {
        $result = Validator::make(['net' => 'cidr'], ['net' => '192.168.1.0/24']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['net' => 'cidr'], ['net' => '2001:db8::/32']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['net' => 'cidr'], ['net' => '192.168.1.0']);
        $this->assertTrue($result->isFailed());

        $result = Validator::make(['net' => 'cidr'], ['net' => '192.168.1.0/33']);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The net must be a valid CIDR notation.', $result->getFailedRules()[0]['message']);
    }

    public function testRequiredArrayKeysRule()
    {
        $result = Validator::make(
            ['user' => 'required_array_keys:id,name'],
            ['user' => ['id' => 1, 'name' => 'Ada', 'email' => 'ada@example.com']]
        );
        $this->assertFalse($result->isFailed());

        $result = Validator::make(
            ['user' => 'required_array_keys:id,name'],
            ['user' => ['id' => 1]]
        );
        $this->assertTrue($result->isFailed());
        $this->assertEquals(
            'The user must contain all required keys.',
            $result->getFailedRules()[0]['message']
        );
    }

    public function testAgeBetweenRule()
    {
        $birthDate = (new \DateTimeImmutable('today'))->modify('-30 years')->format('Y-m-d');
        $result = Validator::make(['dob' => 'age_between:18,65'], ['dob' => $birthDate]);
        $this->assertFalse($result->isFailed());

        $tooYoung = (new \DateTimeImmutable('today'))->modify('-10 years')->format('Y-m-d');
        $result = Validator::make(['dob' => 'age_between:18,65'], ['dob' => $tooYoung]);
        $this->assertTrue($result->isFailed());
        $this->assertEquals(
            'The dob age must be between 18 and 65.',
            $result->getFailedRules()[0]['message']
        );
    }

    public function testEanRule()
    {
        $result = Validator::make(['sku' => 'ean'], ['sku' => '4006381333931']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['sku' => 'ean'], ['sku' => '96385074']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['sku' => 'ean'], ['sku' => '4006381333932']);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The sku must be a valid EAN barcode.', $result->getFailedRules()[0]['message']);
    }

    public function testLocaleRule()
    {
        $result = Validator::make(['lang' => 'locale'], ['lang' => 'en_US']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['lang' => 'locale'], ['lang' => 'pt-BR']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['lang' => 'locale'], ['lang' => 'english']);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The lang must be a valid locale.', $result->getFailedRules()[0]['message']);
    }

    public function testDeclinedRule()
    {
        foreach (['no', 'off', '0', 'false', false, 0] as $value) {
            $result = Validator::make(['terms' => 'declined'], ['terms' => $value]);
            $this->assertFalse($result->isFailed(), 'Failed for value: ' . var_export($value, true));
        }

        $result = Validator::make(['terms' => 'declined'], ['terms' => 'yes']);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The terms must be declined.', $result->getFailedRules()[0]['message']);
    }

    public function testDurationRule()
    {
        $result = Validator::make(['ttl' => 'duration'], ['ttl' => 'PT15M']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['ttl' => 'duration'], ['ttl' => 'P1DT2H']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['ttl' => 'duration'], ['ttl' => '15 minutes']);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The ttl must be a valid ISO 8601 duration.', $result->getFailedRules()[0]['message']);
    }

    public function testCronRule()
    {
        $result = Validator::make(['schedule' => 'cron'], ['schedule' => '*/5 * * * *']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['schedule' => 'cron'], ['schedule' => '0 9 * * 1-5']);
        $this->assertFalse($result->isFailed());

        $result = Validator::make(['schedule' => 'cron'], ['schedule' => '60 * * * *']);
        $this->assertTrue($result->isFailed());

        $result = Validator::make(['schedule' => 'cron'], ['schedule' => '* * *']);
        $this->assertTrue($result->isFailed());
        $this->assertEquals('The schedule must be a valid cron expression.', $result->getFailedRules()[0]['message']);
    }
}
