## Tartalomjegyzék

1. [Alpha](#example-alpha)
2. [Active URL](#example-active-url)
3. [Ascii](#example-ascii)
4. [Date Equals](#example-date-equals)
5. [Distinct](#example-distinct)
6. [Date Format](#example-date-format)
7. [Password](#example-password)
8. [Contains](#example-contains)
9. [Min](#example-min)
10. [Max](#example-max)
11. [In](#example-in)
12. [Date](#example-date)
13. [Digits](#example-digits)
14. [Digits Between](#example-digits-between)
15. [Same](#example-same)
16. [Ip](#example-ip)
17. [Json](#example-json)
18. [Regex](#example-regex)
19. [Base64](#example-base64)
20. [Base64 Image](#example-base64-image)
21. [Present](#example-present)
22. [Different](#example-different)



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

### Example: `Alpha Dash`

This code validates that the `username` field contains only alphabetic characters, numbers, dashes, and underscores. It first checks a valid username (`John_Doe-123`) and then an invalid one (`John Doe!`).

```php
$validationRules = [
    'username' => 'alpha_dash',
];
$dataToValidate = [
    'username' => 'John_Doe-123',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['username'] = 'John Doe!';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `After`

This code validates that the `start_date` field is a date after a given date (`2023-01-01`). It first checks a valid date (`2023-02-01`) and then an invalid one (`2022-12-31`).

```php
$validationRules = [
    'start_date' => 'after:2023-01-01',
];
$dataToValidate = [
    'start_date' => '2023-02-01',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['start_date'] = '2022-12-31';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Before`

This code validates that the `end_date` field is a date before a given date (`2023-12-31`). It first checks a valid date (`2023-11-30`) and then an invalid one (`2024-01-01`).

```php
$validationRules = [
    'end_date' => 'before:2023-12-31',
];
$dataToValidate = [
    'end_date' => '2023-11-30',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['end_date'] = '2024-01-01';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Active URL`

This code validates that the `website` field contains a valid URL with a DNS record. It first checks a valid URL (`https://www.example.com`) and then an invalid one (`https://invalid-url`).

```php
$validationRules = [
    'website' => 'active_url',
];
$dataToValidate = [
    'website' => 'https://www.example.com',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['website'] = 'https://invalid-url';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Ascii`

This code validates that the `text` field contains only ASCII characters. It first checks a valid ASCII string (`Hello`) and then an invalid one (`Héllo`).

```php
$validationRules = [
    'text' => 'ascii',
];
$dataToValidate = [
    'text' => 'Hello',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['text'] = 'Héllo';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Date Equals`

This code validates that the `event_date` field is a date equal to a given date (`2023-05-01`). It first checks a valid date (`2023-05-01`) and then an invalid one (`2023-05-02`).

```php
$validationRules = [
    'event_date' => 'date_equals:2023-05-01',
];
$dataToValidate = [
    'event_date' => '2023-05-01',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['event_date'] = '2023-05-02';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Distinct`

This code validates that the `tags` field does not have any duplicate values. It first checks a valid array (`['php', 'laravel']`) and then an invalid one (`['php', 'php']`).

```php
$validationRules = [
    'tags.*' => 'distinct',
];
$dataToValidate = [
    'tags' => ['php', 'laravel'],
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['tags'] = ['php', 'php'];
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Date Format`

This code validates that the `birth_date` field matches one of the given date formats (`Y-m-d`, `d/m/Y`). It first checks a valid date (`2023-05-01`) and then an invalid one (`01-05-2023`).

```php
$validationRules = [
    'birth_date' => 'date_format:Y-m-d,d/m/Y',
];
$dataToValidate = [
    'birth_date' => '2023-05-01',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['birth_date'] = '01-05-2023';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Password`

This code validates that the `password` field meets the password strength requirements (uppercase, lowercase, digit, special character). It first checks a valid password (`P@ssw0rd`) and then an invalid one (`password`).

```php
$validationRules = [
    'password' => 'password:ulds',
];
$dataToValidate = [
    'password' => 'P@ssw0rd',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['password'] = 'password';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Contains`

This code validates that the `description` field contains the specified substring (`example`). It first checks a valid string (`This is an example.`) and then an invalid one (`This is a test.`).

```php
$validationRules = [
    'description' => 'contains:example',
];
$dataToValidate = [
    'description' => 'This is an example.',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['description'] = 'This is a test.';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Min`

This code validates that the `age` field is at least 18. It first checks a valid age (`20`) and then an invalid one (`16`).

```php
$validationRules = [
    'age' => 'min:18',
];
$dataToValidate = [
    'age' => 20,
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['age'] = 16;
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Max`

This code validates that the `age` field is at most 65. It first checks a valid age (`60`) and then an invalid one (`70`).

```php
$validationRules = [
    'age' => 'max:65',
];
$dataToValidate = [
    'age' => 60,
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['age'] = 70;
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `In`

This code validates that the `status` field is one of the given values (`active`, `inactive`). It first checks a valid status (`active`) and then an invalid one (`pending`).

```php
$validationRules = [
    'status' => 'in:active,inactive',
];
$dataToValidate = [
    'status' => 'active',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['status'] = 'pending';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Date`

This code validates that the `birthday` field is a valid date. It first checks a valid date (`1990-01-01`) and then an invalid one (`not-a-date`).

```php
$validationRules = [
    'birthday' => 'date',
];
$dataToValidate = [
    'birthday' => '1990-01-01',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['birthday'] = 'not-a-date';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Digits`

This code validates that the `phone` field is a numeric value with exactly 10 digits. It first checks a valid phone number (`1234567890`) and then an invalid one (`12345`).

```php
$validationRules = [
    'phone' => 'digits:10',
];
$dataToValidate = [
    'phone' => '1234567890',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['phone'] = '12345';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Digits Between`

This code validates that the `phone` field is a numeric value with a length between 8 and 12 digits. It first checks a valid phone number (`12345678`) and then an invalid one (`1234`).

```php
$validationRules = [
    'phone' => 'digits_between:8,12',
];
$dataToValidate = [
    'phone' => '12345678',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['phone'] = '1234';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Same`

This code validates that the `password_confirmation` field is the same as the `password` field. It first checks matching passwords (`secret` and `secret`) and then non-matching ones (`secret` and `notsecret`).

```php
$validationRules = [
    'password' => 'required',
    'password_confirmation' => 'same:password',
];
$dataToValidate = [
    'password' => 'secret',
    'password_confirmation' => 'secret',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['password_confirmation'] = 'notsecret';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Ip`

This code validates that the `ip_address` field contains a valid IP address. It first checks a valid IP address (`192.168.1.1`) and then an invalid one (`999.999.999.999`).

```php
$validationRules = [
    'ip_address' => 'ip',
];
$dataToValidate = [
    'ip_address' => '192.168.1.1',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['ip_address'] = '999.999.999.999';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Json`

This code validates that the `json_data` field contains a valid JSON string. It first checks a valid JSON string (`{"name": "John"}`) and then an invalid one (`{name: John}`).

```php
$validationRules = [
    'json_data' => 'json',
];
$dataToValidate = [
    'json_data' => '{"name": "John"}',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['json_data'] = '{name: John}';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Regex`

This code validates that the `username` field matches a given regular expression (alphanumeric characters only). It first checks a valid username (`JohnDoe123`) and then an invalid one (`John_Doe!`).

```php
$validationRules = [
    'username' => 'regex:/^[a-zA-Z0-9]+$/',
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

$dataToValidate['username'] = 'John_Doe!';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Base64`

This code validates that the `encoded_data` field contains a valid base64 encoded string. It first checks a valid base64 string (`SGVsbG8gd29ybGQ=`) and then an invalid one (`Hello world`).

```php
$validationRules = [
    'encoded_data' => 'base64',
];
$dataToValidate = [
    'encoded_data' => 'SGVsbG8gd29ybGQ=',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['encoded_data'] = 'Hello world';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Base64 Image`

This code validates that the `image_data` field contains a valid base64 encoded image. It first checks a valid base64 image string and then an invalid one.

```php
$validationRules = [
    'image_data' => 'base64_image',
];
$dataToValidate = [
    'image_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUA...',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['image_data'] = 'invalid_base64_image_data';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Present`

This code validates that the `username` field is present in the data. It first checks a present field (`username` is set) and then a missing field (`username` is not set).

```php
$validationRules = [
    'username' => 'present',
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

unset($dataToValidate['username']);
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```

### Example: `Different`

This code validates that the `new_password` field is different from the `current_password` field. It first checks different passwords (`current_password` is `oldpass` and `new_password` is `newpass`) and then the same passwords (`current_password` and `new_password` are both `oldpass`).

```php
$validationRules = [
    'new_password' => 'different:current_password',
];
$dataToValidate = [
    'current_password' => 'oldpass',
    'new_password' => 'newpass',
];

$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}

$dataToValidate['new_password'] = 'oldpass';
$result = Validator::make($validationRules, $dataToValidate);
if ($result->isFailed()) {
    echo "Validation failed";
} else {
    echo "Validation successful!";
}
```
[<< Back to Readme](../Readme.md)