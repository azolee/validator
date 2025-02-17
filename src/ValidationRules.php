<?php

namespace Azolee\Validator;

use Azolee\Validator\Helpers\ArrayHelper;
use Azolee\Validator\Validators\Base64Validator;

class ValidationRules
{
    public const CUSTOM_RULE = 'custom_rule';

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function array(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_array($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function string(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_null($data) || is_string($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function numeric(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_null($data) || is_numeric($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function boolean(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_bool($data)) {
            return true;
        }
        return is_null($data) || filter_var($data, FILTER_VALIDATE_BOOLEAN) === true;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function not_null(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return !is_null($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function email(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function url(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return filter_var($data, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function min(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_numeric($data)) {
            return $data >= $value;
        }
        if (is_string($data)) {
            return strlen($data) >= $value;
        }
        if (is_array($data)) {
            return count($data) >= $value;
        }
        return false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function max(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_numeric($data)) {
            return $data <= $value;
        }
        if (is_string($data)) {
            return strlen($data) <= $value;
        }
        if (is_array($data)) {
            return count($data) <= $value;
        }
        return false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function in(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return in_array($data, explode(',', $value));
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function date(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return strtotime($data) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function alpha(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return extension_loaded('ctype') ? ctype_alpha($data) : (preg_match('/^[a-zA-Z]+$/', $data) === 1);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function alpha_num(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return extension_loaded('ctype') ? ctype_alnum($data) : (preg_match('/^[a-zA-Z0-9]+$/', $data) === 1);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function digits(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_numeric($data) && strlen((string)$data) == $value;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function digits_between(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        [$min, $max] = explode(',', $value);
        $length = strlen((string)$data);
        return is_numeric($data) && $length >= $min && $length <= $max;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function different(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return !static::same($data, $key, $value, $dataToValidate);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function same(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $fieldToCompare = ArrayHelper::parseNestedData($dataToValidate, $value)[0] ?? ['value' => null];
        return $data === $fieldToCompare['value'];
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function ip(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return filter_var($data, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function json(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        json_decode($data);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function regex(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return preg_match($value, $data) === 1;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function required(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return !empty($data);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function contains(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return str_contains($data, $value);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function alpha_dash(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $data) === 1;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function after(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return strtotime($data) > strtotime($value);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function before(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return strtotime($data) < strtotime($value);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function active_url(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return filter_var($data, FILTER_VALIDATE_URL) !== false && checkdnsrr(parse_url($data, PHP_URL_HOST), 'A');
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function ascii(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return mb_check_encoding($data, 'ASCII');
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function date_equals(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return date('Y-m-d', strtotime($data)) === date('Y-m-d', strtotime($value));
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function distinct(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $strict = false;
        $ignoreCase = false;

        if ($value) {
            $params = explode(',', $value);
            $strict = in_array('strict', $params);
            $ignoreCase = in_array('ignore_case', $params);
        }

        $keyParts = explode('.', $key);
        $currentKey = array_pop($keyParts); // get the key to validate
        array_pop($keyParts); // remove the * key

        $values = ArrayHelper::parseNestedData($dataToValidate, implode('.', $keyParts));
        $values = array_column(
            (array_column($values, 'value')[0] ?? []),
            $currentKey
        );

        if ($ignoreCase) {
            $values = array_map('strtolower', $values);
        }

        return count($values) === count(array_unique($values, $strict ? SORT_STRING : SORT_REGULAR));
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function date_format(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $formats = explode(',', $value);
        foreach ($formats as $format) {
            $parsedDate = date_create_from_format($format, $data);
            if ($parsedDate && $parsedDate->format($format) === $data) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function password(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $config = [
            'require_uppercase' => false,
            'require_lowercase' => false,
            'require_digit' => false,
            'require_special' => false,
        ];

        if ($value) {
            $config['require_uppercase'] = str_contains($value, 'u');
            $config['require_lowercase'] = str_contains($value, 'l');
            $config['require_digit'] = str_contains($value, 'd');
            $config['require_special'] = str_contains($value, 's');
        }

        $pattern = '/^';
        if ($config['require_lowercase']) {
            $pattern .= '(?=.*[a-z])';
        }
        if ($config['require_uppercase']) {
            $pattern .= '(?=.*[A-Z])';
        }
        if ($config['require_digit']) {
            $pattern .= '(?=.*\d)';
        }
        if ($config['require_special']) {
            $pattern .= '(?=.*[.,~@$!%*?&])';
        }
        $pattern .= '.+$/';

        return preg_match($pattern, $data) === 1;
    }

    /**
     * Validates that the data is a valid base64 string.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function base64(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return Base64Validator::validateString($data, $key, $value, $dataToValidate);
    }

    /**
     * Validates that the data is a valid base64 encoded image.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function base64_image(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return Base64Validator::validateImage($data, $key, $value, $dataToValidate);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function present(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $nestedData = ArrayHelper::parseNestedData($dataToValidate, $key);
        return !empty($nestedData);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function charset(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (!in_array($value, mb_list_encodings(), true)) {
            return false;
        }

        return mb_check_encoding($data, $value);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function uuid(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return preg_match('/^\{?[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}\}?$/', $data) === 1;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function slug(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $data) === 1;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function iban(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $iban = strtolower(str_replace(' ', '', $data));
        $countries = [
            'al' => 28, 'ad' => 24, 'at' => 20, 'az' => 28, 'bh' => 22, 'be' => 16, 'ba' => 20, 'br' => 29,
            'bg' => 22, 'cr' => 21, 'hr' => 21, 'cy' => 28, 'cz' => 24, 'dk' => 18, 'do' => 28, 'ee' => 20,
            'fo' => 18, 'fi' => 18, 'fr' => 27, 'ge' => 22, 'de' => 22, 'gi' => 23, 'gr' => 27, 'gl' => 18,
            'gt' => 28, 'hu' => 28, 'is' => 26, 'ie' => 22, 'il' => 23, 'it' => 27, 'jo' => 30, 'kz' => 20,
            'kw' => 30, 'lv' => 21, 'lb' => 28, 'li' => 21, 'lt' => 20, 'lu' => 20, 'mk' => 19, 'mt' => 31,
            'mr' => 27, 'mu' => 30, 'mc' => 27, 'md' => 24, 'me' => 22, 'nl' => 18, 'no' => 15, 'pk' => 24,
            'ps' => 29, 'pl' => 28, 'pt' => 25, 'qa' => 29, 'ro' => 24, 'sm' => 27, 'sa' => 24, 'rs' => 22,
            'sk' => 24, 'si' => 19, 'es' => 24, 'se' => 24, 'ch' => 21, 'tn' => 24, 'tr' => 26, 'ae' => 23,
            'gb' => 22, 'vg' => 24
        ];

        $countryCode = substr($iban, 0, 2);
        if (!isset($countries[$countryCode]) || strlen($iban) != $countries[$countryCode]) {
            return false;
        }

        $iban = substr($iban, 4) . substr($iban, 0, 4);
        $iban = str_replace(
            range('a', 'z'),
            range(10, 35),
            $iban
        );

        $checksum = intval(substr($iban, 0, 1));
        for ($i = 1; $i < strlen($iban); $i++) {
            $checksum = ($checksum * 10 + intval(substr($iban, $i, 1))) % 97;
        }

        return $checksum == 1;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function hex_color(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return preg_match('/^#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $data) === 1;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function timezone(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return in_array($data, timezone_identifiers_list(), true);
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function min_words(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $wordCount = str_word_count($data);
        return $wordCount >= $value;
    }

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function max_words(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        $wordCount = str_word_count($data);
        return $wordCount <= $value;
    }
}
