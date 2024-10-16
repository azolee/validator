# Laravel-like Validator

The Laravel-like Validator is a PHP validation library designed to help you validate data structures with ease. It supports various validation rules, custom rules, and nested data validation.

## Installation

You can install the package via Composer:

```bash
composer require azolee/validator
```

## Usage

### Basic Usage

To use the validator, you need to define your validation rules and the data to be validated. Then, call the `Validator::make` method.

```php
use Azolee\Validator\Validator;

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
    'images.*.role' => 'string',
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
            'url' => 'image1.jpg'
            'role' => 'profile_photo',
        ],
        [
            'url' => 'image2.jpg'
            'role' => 'album_photo',
            'description' => 'This is a photo of me.',
        ],
    ],
];

$result = Validator::make($validationRules, $dataToValidate);

if ($result->isFailed()) {
    echo "Validation failed!";
    print_r($result->getFailedRules());
} else {
    echo "Validation passed!";
}
```

### Custom Rules

You can also define custom validation rules using closures.

```php
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

if ($result->isFailed()) {
    echo "Validation failed!";
    print_r($result->getFailedRules());
} else {
    echo "Validation passed!";
}
```

### Exception Handling

The validator can throw exceptions for invalid rules or data if the `silent` parameter is set to `false`.

```php
use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Exceptions\ValidationException;

try {
    $validationRules = [
        'name' => 123, // Invalid rule
    ];
    $dataToValidate = [
        'name' => 'John Doe',
    ];

    Validator::make($validationRules, $dataToValidate, false);
} catch (InvalidValidationRule $e) {
    echo "Caught an InvalidValidationRule exception: " . $e->getMessage();
} catch (ValidationException $e) {
    echo "Caught a ValidationException: " . $e->getMessage();
}
```

## Testing

To run the tests, use PHPUnit:

```bash
vendor/bin/phpunit
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
