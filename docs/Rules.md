# The list of built-in validation rules

This document describes the validation rules available in the `Azolee\Validator\ValidationRules` class.

## Available Rules

### `array`
- **Description**: Validates that the data is an array.
- **Usage**: `array`

### `string`
- **Description**: Validates that the data is a string or null.
- **Usage**: `string`

### `numeric`
- **Description**: Validates that the data is numeric or null.
- **Usage**: `numeric`

### `boolean`
- **Description**: Validates that the data is a boolean or can be interpreted as a boolean.
- **Usage**: `boolean`

### `not_null`
- **Description**: Validates that the data is not null.
- **Usage**: `not_null`

### `email`
- **Description**: Validates that the data is a valid email address.
- **Usage**: `email`

### `url`
- **Description**: Validates that the data is a valid URL.
- **Usage**: `url`

### `min`
- **Description**: Validates that the data is greater than or equal to a minimum value. Supports numeric, string, and array data types.
- **Usage**: `min:value`

### `max`
- **Description**: Validates that the data is less than or equal to a maximum value. Supports numeric, string, and array data types.
- **Usage**: `max:value`

### `in`
- **Description**: Validates that the data is one of the given values.
- **Usage**: `in:value1,value2,...`

### `date`
- **Description**: Validates that the data is a valid date.
- **Usage**: `date`

### `alpha`
- **Description**: Validates that the data contains only alphabetic characters.
- **Usage**: `alpha`

### `alpha_num`
- **Description**: Validates that the data contains only alphanumeric characters.
- **Usage**: `alpha_num`

### `digits`
- **Description**: Validates that the data is numeric and has an exact length.
- **Usage**: `digits:length`

### `digits_between`
- **Description**: Validates that the data is numeric and its length is between a minimum and maximum value.
- **Usage**: `digits_between:min,max`

### `same`
- **Description**: Validates that the data is equal to the value of another field.
- **Usage**: `same:other_field`

### `different`
- **Description**: Validates that the data is different from the value of another field.
- **Usage**: `different:other_field`

### `ip`
- **Description**: Validates that the data is a valid IP address.
- **Usage**: `ip`

### `json`
- **Description**: Validates that the data is a valid JSON string.
- **Usage**: `json`

### `regex`
- **Description**: Validates that the data matches a given regular expression.
- **Usage**: `regex:pattern`

### `required`
- **Description**: Validates that the data is not empty.
- **Usage**: `required`

### `alpha_dash`
- **Description**: Validates that the data contains only alphabetic characters, numbers, dashes, and underscores.
- **Usage**: `alpha_dash`

### `after`
- **Description**: Validates that the data is a date after a given date.
- **Usage**: `after:date`

### `before`
- **Description**: Validates that the data is a date before a given date.
- **Usage**: `before:date`

### `active_url`
- **Description**: Validates that the data is a valid URL and the domain has a DNS record.
- **Usage**: `active_url`

### `ascii`
- **Description**: Validates that the data contains only ASCII characters.
- **Usage**: `ascii`

### `date_equals`
- **Description**: Validates that the data is a date equal to a given date.
- **Usage**: `date_equals:date`

### `distinct`
- **Description**: Validates that the field under validation does not have any duplicate values when validating arrays.
- **Usage**: `distinct`
- **Parameters**:
  - `strict`: Use strict comparisons.
  - `ignore_case`: Ignore capitalization differences.

### `date_format`
- **Description**: Validates that the data matches one of the given date formats.
- **Usage**: `date_format:format1,format2,...`

### `password`
- **Description**: Validates that the data meets the password strength requirements.
- **Usage**: `password:ulds`
- **Parameters**:
  - `u`: Require at least one uppercase letter.
  - `l`: Require at least one lowercase letter.
  - `d`: Require at least one digit.
  - `s`: Require at least one special character: `.,~@$!%*?&`.

### `contains`
- **Description**: Validates that the data contains the specified substring.
- **Usage**: `contains:substring`

### `base64`
- **Description**: Validates that the data is a valid base64 encoded string.
- **Usage**: `base64[:{chunk_size}]` or `base64[:chunk_size:{chunk_size}]`
- **Parameters**:
  - `chunk_size`: The size of the chunks in which the data is split.

### `base64_image`
- **Description**: Validates that the data is a valid base64 encoded image with a data URL scheme.
- **Usage**: `base64_image`

### `present`
- **Description**: Validates that the field is present in the data.
- **Usage**: `present`

### `charset`
- **Description**: Validates that the data is a valid string in the specified charset.
- **Usage**: charset:charset
- **Parameters**:
  - `charset`: The character set to validate against (e.g., UTF-8, ISO-8859-1).

### `uuid`
- **Description**: Validates that the data is a valid UUID.
- **Usage**: `uuid`

### `slug`
- **Description**: Validates that the data is a valid slug (lowercase letters, numbers, and hyphens).
- **Usage**: `slug`

### `iban`
- **Description**: Validates that the data is a valid IBAN.
- **Usage**: `iban`

### `hex_color`
- **Description**: Validates that the data is a valid hex color code.
- **Usage**: `hex_color`

### `timezone`
- **Description**: Validates that the data is a valid timezone identifier.
- **Usage**: `timezone`

### `min_words`
- **Description**: Validates that the given string contains at least a specified number of words.
- **Usage**: `min_words:3`
- **Parameters**:
  - `value` (int): The minimum number of words required.
  
### `max_words`
- **Description**: Validates that the given string contains no more than a specified number of words.
- **Usage**: `max_words:5`
- **Parameters**:
  - `value` (int): The maximum number of words allowed.

### `html_safe`
- **Description**: Validates that the string contains only safe HTML.
- **Usage**: `html_safe` or `html_safe:a,p,br`
- **Notes**:
    - Disallows dangerous tags (script, iframe, object, embed, link, meta, style, form, input, button, textarea, select).
    - Disallows event attributes (on*) and javascript: URLs.
    - Allowed tags (default): a, b, strong, em, i, u, br, p, ul, ol, li, span.
    - Allowed attributes: href, title, rel, target, class, id, alt.

### `mac_address`
- **Description**: Validates that the value is a valid MAC address.
- **Usage**: `mac_address`
- **Supported formats**:
    - `00:1A:2B:3C:4D:5E`
    - `00-1A-2B-3C-4D-5E`
    - `001A.2B3C.4D5E`
    - `001A2B3C4D5E`

### `domain`
- **Description**: Validates that the value is a valid domain name (hostname), not a URL.
- **Usage**: `domain`
- **Notes**:
    - Syntax-only check using PHP’s FILTER_VALIDATE_DOMAIN with FILTER_FLAG_HOSTNAME.

### `bail`
- **Description**: Stops further validation for a field as soon as one rule fails.
- **Usage**: `bail`

### `bic`
- **Description**: Validates that the data is a valid SWIFT/BIC code.
- **Usage**: `bic`
- **Notes**:
    - Accepts 8-character (bank code) or 11-character (with branch) codes.
    - Spaces are ignored; comparison is case-insensitive.

### `cidr`
- **Description**: Validates that the data is a valid CIDR notation (IPv4 or IPv6 with prefix length).
- **Usage**: `cidr`
- **Notes**:
    - Requires `ip/prefix` form (e.g. `192.168.1.0/24`, `2001:db8::/32`).
    - IPv4 prefix must be 0–32; IPv6 prefix must be 0–128.
    - Unlike `ip`, a bare address without a prefix fails.

### `required_array_keys`
- **Description**: Validates that an array contains all of the given keys.
- **Usage**: `required_array_keys:id,name,email`
- **Parameters**:
  - Comma-separated list of required keys.
- **Notes**:
    - Extra keys are allowed; only missing required keys fail.

### `age_between`
- **Description**: Validates that a date of birth yields an age within an inclusive range.
- **Usage**: `age_between:18,65`
- **Parameters**:
  - `min,max` (int): Inclusive age bounds.
- **Notes**:
    - Parses the value as a date and compares against today's age.

### `ean`
- **Description**: Validates that the data is a valid EAN-8 or EAN-13 barcode (checksum included).
- **Usage**: `ean`
- **Notes**:
    - Accepts 8- or 13-digit numeric codes.
    - Checksum digit is verified.

### `locale`
- **Description**: Validates that the data is a locale / language tag.
- **Usage**: `locale`
- **Notes**:
    - Accepts forms like `en`, `en_US`, and `pt-BR`.

### `declined`
- **Description**: Validates that the data is a declined boolean-like value.
- **Usage**: `declined`
- **Accepted values**:
    - `no`, `off`, `0`, `false` (string)
    - `false` (boolean)
    - `0` (int/float)
- **Notes**:
    - Unlike most rules, `null` fails (the field must explicitly be declined).

### `duration`
- **Description**: Validates that the data is a valid ISO 8601 duration.
- **Usage**: `duration`
- **Examples**:
    - `PT15M`
    - `P1DT2H`
    - `P1Y2M3DT4H5M6S`

### `cron`
- **Description**: Validates that the data is a valid 5-field cron expression.
- **Usage**: `cron`
- **Notes**:
    - Expects minute, hour, day-of-month, month, day-of-week.
    - Supports `*`, lists, ranges, and steps (e.g. `*/5`, `1-5`, `0,15,30,45`).

### `decimal`
- **Description**: Validates that the data is numeric with a limited number of decimal places.
- **Usage**: `decimal` or `decimal:2` or `decimal:2,2`
- **Parameters**:
  - One value: maximum fraction digits (default `2`, minimum `0`).
  - Two values: minimum and maximum fraction digits.
- **Notes**:
    - Scientific notation (e.g. `1e2`) is rejected.

### `money`
- **Description**: Validates a monetary amount: numeric, non-negative, with limited decimals.
- **Usage**: `money` or `money:2`
- **Parameters**:
  - Max decimal places (default `2`).

### `positive`
- **Description**: Validates that the data is a number greater than zero.
- **Usage**: `positive`
- **Notes**:
    - `0` fails; use `min:0` when zero should be allowed.

### `currency`
- **Description**: Validates an ISO 4217 currency code.
- **Usage**: `currency`
- **Notes**:
    - Accepts codes such as `EUR`, `HUF`, `USD` (case-insensitive).

### `amount_between`
- **Description**: Validates that a numeric amount is within an inclusive range.
- **Usage**: `amount_between:0.01,9999.99`
- **Parameters**:
  - `min,max` numeric bounds.

### `credit_card`
- **Description**: Validates a credit/debit card number using the Luhn checksum.
- **Usage**: `credit_card`
- **Notes**:
    - Spaces and dashes are ignored.
    - Length must be between 13 and 19 digits.

### `card_brand`
- **Description**: Validates that a card number matches one of the given brands.
- **Usage**: `card_brand:visa,mastercard` or `card_brand`
- **Parameters**:
  - Comma-separated brands: `visa`, `mastercard`, `amex`, `discover`.
- **Notes**:
    - Without parameters, all supported brands are allowed.

### `cvv`
- **Description**: Validates a card CVV/CVC code.
- **Usage**: `cvv` or `cvv:3` / `cvv:4`
- **Notes**:
    - Default accepts 3 or 4 digits.

### `expiry_date`
- **Description**: Validates a card expiry date that is not in the past.
- **Usage**: `expiry_date` or `expiry_date:m/y`
- **Notes**:
    - Default format is `m/y`. Also supports formats such as `m/Y`, `ym`, `Y-m`.
    - Compared against the end of the expiry month.

### `account_number`
- **Description**: Validates a domestic-style account number (digits only).
- **Usage**: `account_number` or `account_number:8,17`
- **Parameters**:
  - Optional exact length, or `min,max` length.
- **Notes**:
    - Default length range is 4–34. Spaces and dashes are ignored.
    - For IBAN numbers use `iban` instead.

### `vat`
- **Description**: Validates a VAT / tax identification number.
- **Usage**: `vat` or `vat:EU` or `vat:RO`
- **Parameters**:
  - Optional country/region filter (`EU` or an ISO country code such as `RO`, `DE`).
- **Notes**:
    - EU mode expects a country prefix plus a country-specific alphanumeric body.

### `percentage`
- **Description**: Validates a percentage between 0 and 100 (inclusive).
- **Usage**: `percentage` or `percentage:2`
- **Parameters**:
  - Optional maximum decimal places.

### `minor_units`
- **Description**: Validates a non-negative integer amount in minor units (e.g. cents).
- **Usage**: `minor_units`
- **Notes**:
    - Accepts integers and digit-only strings. Floats such as `10.5` fail.

## Callable Rules

Callable rules are custom validation rules defined as closures or callable functions. They should return a boolean value indicating whether the validation passed or failed.

### Example

```php
$validationRules = [
    'field_name' => function ($data, $key, $dataToValidate) {
        // Custom validation logic
        return $data === 'expected_value';
    },
];
```