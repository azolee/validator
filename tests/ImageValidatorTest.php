<?php

namespace Tests;

use Azolee\Validator\Exceptions\InvalidImageHeightException;
use Azolee\Validator\Exceptions\InvalidImageRatioException;
use Azolee\Validator\Exceptions\InvalidImageWidthException;
use Azolee\Validator\Validator;
use Azolee\Validator\Validators\ImageValidator;
use PHPUnit\Framework\TestCase;
use Azolee\Validator\Rule;

class ImageValidatorTest extends TestCase
{
    protected static function getDataToValidate($fileName)
    {
        return [
            'images' => [
                'tmp_name' => __DIR__ . '/images/' . $fileName,
            ],
        ];
    }
    public function testValidImage()
    {
        $dataToValidate = [
            'tmp_name' => __DIR__ . '/images/valid_image.jpg',
        ];

        $validator = new ImageValidator(
            allowedMimeTypes: ['image/jpeg', 'image/png'],
            maxSize: 2 * 1024 * 1024, // 2MB
            minRatio: 0.5,
            maxRatio: 2.0,
            minWidth: 100,
            maxWidth: 1920,
            minHeight: 100,
            maxHeight: 1080
        );

        $result = $validator->validate($dataToValidate);
        $this->assertTrue($result);
    }

    public function testInvalidImageRatio()
    {
        $dataToValidate = static::getDataToValidate('invalid_ratio.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 1.0, 100, 2000, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidMimeType()
    {
        $dataToValidate = static::getDataToValidate('invalid_mime_type.txt');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 2.0, 100, 2000, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidRatio()
    {
        $dataToValidate = static::getDataToValidate('invalid_ratio.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 1.0, 100, 2000, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidWidth()
    {
        $dataToValidate = static::getDataToValidate('invalid_ratio.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 2.0, 200, 2000, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidHeight()
    {
        $dataToValidate = static::getDataToValidate('invalid_ratio.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 2.0, 100, 2000, 200, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidMinWidth()
    {
        $dataToValidate = static::getDataToValidate('invalid_ratio.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 2.0, 1000, 2000, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidMinHeight()
    {
        $dataToValidate = static::getDataToValidate('invalid_ratio.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 2.0, 100, 2000, 1000, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidMaxSize()
    {
        $dataToValidate = static::getDataToValidate('valid_image.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000, 0.5, 2.0, 100, 2000, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testFileDoesNotExist()
    {
        $dataToValidate = static::getDataToValidate('non_existent_file.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 2.0, 100, 2000, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidImageWidth()
    {
        $dataToValidate = static::getDataToValidate('valid_image.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', minWidth: 550, maxWidth: 600);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testInvalidImageHeight()
    {
        $dataToValidate = static::getDataToValidate('invalid_ratio.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', minHeight: 200, maxHeight: 500);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testImageWidthTooSmall()
    {
        $dataToValidate = static::getDataToValidate('small_width.jpg');
        $validationRules = [
            'images' => function ($data) {
                return Rule::image($data, 'image/jpeg|image/png', 1000000, 0.5, 2.0, 500, 600, 100, 2000);
            },
        ];

        $result = Validator::make($validationRules, $dataToValidate);
        $this->assertTrue($result->isFailed());
    }

    public function testImageRatioTooLarge()
    {
        $this->expectException(InvalidImageRatioException::class);
        $this->expectExceptionMessage("Image ratio is too large.");

        $dataToValidate = [
            'tmp_name' => __DIR__ . '/images/valid_image.jpg',
        ];

        $validator = new ImageValidator(maxRatio: 0.9);
        $validator->validate($dataToValidate);
    }

    public function testImageWidthTooLarge()
    {
        $this->expectException(InvalidImageWidthException::class);
        $this->expectExceptionMessage("Image width is too large.");

        $dataToValidate = [
            'tmp_name' => __DIR__ . '/images/valid_image.jpg',
        ];

        $validator = new ImageValidator(maxWidth: 400);
        $validator->validate($dataToValidate);
    }

    public function testImageHeightTooSmall()
    {
        $this->expectException(InvalidImageHeightException::class);
        $this->expectExceptionMessage("Image height is too small.");

        $dataToValidate = [
            'tmp_name' => __DIR__ . '/images/valid_image.jpg',
        ];

        $validator = new ImageValidator(minHeight: 600);
        $validator->validate($dataToValidate);
    }
}