<?php

namespace Tests;

use Azolee\Validator\Helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function testParseNestedData()
    {
        $dataToValidate = [
            'user' => [
                'name' => 'John Doe',
                'address' => [
                    'city' => 'New York',
                    'street' => 'First Avenue',
                ],
            ],
            'items' => [
                ['id' => 1, 'name' => 'Item 1'],
                ['id' => 2, 'name' => 'Item 2'],
            ],
        ];

        // Test single level key
        $result = ArrayHelper::parseNestedData($dataToValidate, 'user.name');
        $this->assertEquals($dataToValidate['user']['name'], $result[0]['value']);

        // Test nested key
        $result = ArrayHelper::parseNestedData($dataToValidate, 'user.address.city');
        $this->assertEquals($dataToValidate['user']['address']['city'], $result[0]['value']);

        // Test non-existing key
        $result = ArrayHelper::parseNestedData($dataToValidate, 'user.phone');
        $this->assertEquals([], $result);

        // Test wildcard key
        $result = ArrayHelper::parseNestedData($dataToValidate, 'items.*.name');
        foreach($result as $key => $item) {
            $this->assertEquals($dataToValidate['items'][$key]['name'], $item['value']);
        }
    }
}