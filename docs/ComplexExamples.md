### Example: `Using a Class Method for Validation`

This code uses a custom validation rule defined as a class method to ensure that the `user.name` field is not `John Doe`. It first checks a valid name (`John Smith`) and then an invalid one (`John Doe`).

```php
use Tests\Helpers\CustomRules;

$validationRules = [
    'user.name' => [[CustomRules::class, 'isNotJohnDoe'], 'string'],
];

$dataToValidate = [
    'user' => [
        'name' => 'John Smith'
    ],
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['user']['name'] = 'John Doe';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Validating Nested Data Structures`

This code validates a complex nested data structure with multiple fields and rules. It checks various fields like `user.name`, `user.age`, `address.city`, and `images.*.url` to ensure they meet the specified validation criteria.

```php
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
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Validating Nested Data with Errors`

This code validates a complex nested data structure and ensures that invalid data fails the validation. It checks various fields like `user.name`, `user.age`, `address.city`, and `images.*.url` with invalid values to ensure they do not meet the specified validation criteria.

```php
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
            'role' => 'just_an_image', // Invalid role
            'description' => null, // Invalid description
        ],
    ],
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Using an Object Method for Validation`

This code uses a custom validation rule defined as an object method to ensure that the `user.name` field is not `John Doe`. It first checks a valid name (`John Smith`) and then an invalid one (`John Doe`).

```php
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
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['user']['name'] = 'John Doe';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Using a Closure for Validation`

This code uses a custom validation rule defined as a closure to ensure that the `user.name` field is `John Doe`. It checks a valid name (`John Doe`).

```php
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
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Validating Multiple Rules`

This code validates that the `username` field meets multiple criteria: it is required, a string, and alphanumeric. It first checks a valid username (`johndoe123`) and then an invalid one (`''`).

```php
$validationRules = [
    'username' => 'required|string|alpha_num',
];
$dataToValidate = [
    'username' => 'johndoe123',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['username'] = '';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Validating Image Files`

This code uses the `Validator` class with custom rules to validate image files.

```php
use Azolee\Validator\Validator;
use Azolee\Validator\Rule;

$dataToValidate = [
    'images' => [
        'tmp_name' => __DIR__ . '/images/valid_image.jpg',
    ],
];

$validationRules = [
    'images' => function ($data) {
        return Rule::image(
            $data,
            allowedMimeTypes: ['image/jpeg', 'image/png'],
            maxSize: 2 * 1024 * 1024, // 2MB
            minRatio: 0.5,
            maxRatio: 2.0,
            minWidth: 100,
            maxWidth: 1920,
            minHeight: 100,
            maxHeight: 1080
        );
    },
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Image validation failed";
} else {
    echo "Image validation successful!";
}
```

[<< Back to Readme](../Readme.md)