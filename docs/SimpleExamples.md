### Example: `Alpha`

This code validates that the `username` field contains only alphabetic characters. It first checks a valid username (`JohnDoe`) and then an invalid one (`JohnDoe123`).

```php
$validationRules = [
    'username' => 'alpha',
];
$dataToValidate = [
    'username' => 'JohnDoe',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['username'] = 'JohnDoe123';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `AlphaNum`

This code ensures that the `username` field contains only alphanumeric characters. It first checks a valid username (`JohnDoe123`) and then an invalid one (`JohnDoe123!`).

```php
$validationRules = [
    'username' => 'alpha_num',
];
$dataToValidate = [
    'username' => 'JohnDoe123',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['username'] = 'JohnDoe123!';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Numeric`

This code checks that the `age` field contains numeric values. It first validates a correct numeric value (`30`) and then an incorrect one (`thirty`).

```php
$validationRules = [
    'age' => 'numeric',
];
$dataToValidate = [
    'age' => 30,
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['age'] = 'thirty';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Boolean`

This code ensures that the `is_active` field contains boolean values. It first checks a valid boolean value (`true`) and then an invalid one (`not_a_boolean`).

```php
$validationRules = [
    'is_active' => 'boolean',
];
$dataToValidate = [
    'is_active' => true,
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['is_active'] = 'not_a_boolean';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `String`

This code validates that the `name` field contains string values. It first checks a valid string (`John Doe`) and then an invalid one (`12345`).

```php
$validationRules = [
    'name' => 'string',
];
$dataToValidate = [
    'name' => 'John Doe',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['name'] = 12345;
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Required`

This code ensures that the `name` field is required. It first checks a valid non-empty value (`John Doe`) and then an invalid empty value (`''`).

```php
$validationRules = [
    'name' => 'required',
];
$dataToValidate = [
    'name' => 'John Doe',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['name'] = '';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

[<< Back to Readme](../Readme.md)