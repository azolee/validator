# Validation Rules

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

### `not_equals_field`
- **Description**: Validates that the data is not equal to the value of another field.
- **Usage**: `not_equals_field:other_field`

### `email`
- **Description**: Validates that the data is a valid email address.
- **Usage**: `email`

### `url`
- **Description**: Validates that the data is a valid URL.
- **Usage**: `url`

### `min`
- **Description**: Validates that the data is greater than or equal to a minimum value.
- **Usage**: `min:value`

### `max`
- **Description**: Validates that the data is less than or equal to a maximum value.
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

[<< Back to Readme](Readme.md)