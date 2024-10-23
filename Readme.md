# The Laravel-like data validator for PHP

[![Latest Version](https://img.shields.io/packagist/v/azolee/validator.svg?style=flat-square)](https://packagist.org/packages/azolee/validator)

The Laravel-like Validator is a PHP validation library designed to help you validate data structures with ease. It supports various validation rules, custom rules, and nested data validation.

## Installation

You can install the package via Composer:

```bash
composer require azolee/validator
```

## Usage

For a complete list of available validation rules, please refer to the [Validation Rules](docs/Rules.md) document.

### Basic Usage

To use the validator, you need to define your validation rules and the data to be validated. Then, call the `Validator::make` method.

```php
use Azolee\Validator\Validator;

$validationRules = [
    'user.name' => 'string',
    'user.age' => 'numeric',
    'user.email' => ['email', 'not_null'],
    'user.website' => ['url'],
    'user.password' => ['string', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'],
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
        'password' => 'secret',
        'password_confirmation' => 'secret',
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

    Validator::config(['silent' => false])->make($validationRules, $dataToValidate);
} catch (InvalidValidationRule $e) {
    echo "Caught an InvalidValidationRule exception: " . $e->getMessage();
} catch (ValidationException $e) {
    echo "Caught a ValidationException: " . $e->getMessage();
}
```
## Additional Examples

For more detailed examples, please refer to the following documents:
- [Simple Examples](docs/SimpleExamples.md)
- [Complex Examples](docs/ComplexExamples.md)

## Testing

To run the tests, use PHPUnit:

```bash
vendor/bin/phpunit
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
