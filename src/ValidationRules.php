<?php

namespace Azolee\Validator;

use Azolee\Validator\Helpers\ArrayHelper;
use Azolee\Validator\Validators\Base64Validator;

class ValidationRules
{
    public const CUSTOM_RULE = 'custom_rule';
    public const BAIL_RULE = 'bail';

    /**
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function array(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        return is_null($data) || is_array($data);
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
        return is_null($data) || filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
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
        return is_null($data) || filter_var($data, FILTER_VALIDATE_URL) !== false;
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
        if (is_null($data)) {
            return true;
        }
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
        if (is_null($data)) {
            return true;
        }
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
        if (is_null($data)) {
            return true;
        }
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
        return is_null($data) || strtotime($data) !== false;
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
        if (is_null($data)) {
            return true;
        }        return extension_loaded('ctype') ? ctype_alpha($data) : (preg_match('/^[a-zA-Z]+$/', $data) === 1);
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
        if (is_null($data)) {
            return true;
        }        return extension_loaded('ctype') ? ctype_alnum($data) : (preg_match('/^[a-zA-Z0-9]+$/', $data) === 1);
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
        return is_null($data) || (is_numeric($data) && strlen((string)$data) == $value);
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
        if (is_null($data)) {
            return true;
        }

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
        return is_null($data) || !static::same($data, $key, $value, $dataToValidate);
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
        if (is_null($data)) {
            return true;
        }

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
        return is_null($data) || filter_var($data, FILTER_VALIDATE_IP) !== false;
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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

        return preg_match('/^[a-zA-Z0-9_-]+$/', $data ?? "") === 1;
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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

        return preg_match('/^\{?[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}\}?$/', $data ?? "") === 1;
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
        if (is_null($data)) {
            return true;
        }

        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $data ?? "") === 1;
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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

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
        if (is_null($data)) {
            return true;
        }

        $wordCount = str_word_count($data);
        return $wordCount <= $value;
    }

    /**
     * Validates that the given string is safe HTML.
     * - Disallows dangerous tags like: script, iframe, object, embed, link, meta, style, form, input, button, textarea, select
     * - Disallows event handler attributes (on*)
     * - Disallows javascript: URLs
     * - Limits tags to an allowed list (default: a,b,strong,em,i,u,br,p,ul,ol,li,span). You can override via comma-separated list in $value.
     * - Limits attributes to a safe set: href,title,rel,target,class,id,alt
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Comma-separated allowed tags (e.g. "a,p,br")
     * @param array $dataToValidate
     * @return bool
     */
    public static function html_safe(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }
        if (!is_string($data)) {
            return false;
        }

        $allowedTags = $value
            ? array_values(array_filter(array_map(static fn($t) => strtolower(trim($t)), explode(',', (string)$value))))
            : ['a', 'b', 'strong', 'em', 'i', 'u', 'br', 'p', 'ul', 'ol', 'li', 'span'];

        $disallowedTags = ['script', 'iframe', 'object', 'embed', 'link', 'meta', 'style', 'form', 'input', 'button', 'textarea', 'select'];
        $safeAttributes = ['href', 'title', 'rel', 'target', 'class', 'id', 'alt'];

        $lower = strtolower($data);

        foreach ($disallowedTags as $tag) {
            if (strpos($lower, "<{$tag}") !== false || strpos($lower, "</{$tag}") !== false) {
                return false;
            }
        }
        if (stripos($lower, 'javascript:') !== false) {
            return false;
        }
        if (preg_match('/\son[a-z0-9_-]+\s*=/i', $lower) === 1) {
            return false;
        }

        if (preg_match_all('/<\s*\/?\s*([a-z0-9]+)([^>]*)>/i', $data, $matches)) {
            $tags = $matches[1];
            $attrs = $matches[2];

            foreach ($tags as $tag) {
                $t = strtolower($tag);
                if (!in_array($t, $allowedTags, true)) {
                    return false;
                }
            }

            foreach ($attrs as $attrChunk) {
                if (trim($attrChunk) === '') {
                    continue;
                }

                if (preg_match('/\bon[a-z0-9_-]+\b/i', $attrChunk) === 1) {
                    return false;
                }

                if (preg_match_all('/([a-z0-9:-]+)\s*=\s*("[^"]*"|\'[^\']*\'|[^"\'>\s]+)/i', $attrChunk, $attrMatches)) {
                    $names = $attrMatches[1];
                    $values = $attrMatches[2];
                    foreach ($names as $idx => $name) {
                        $an = strtolower($name);
                        if (!in_array($an, $safeAttributes, true)) {
                            return false;
                        }
                        if (in_array($an, ['href', 'src'], true)) {
                            $val = strtolower(trim($values[$idx], " \t\n\r\0\x0B\"'"));
                            if (str_starts_with($val, 'javascript:')) {
                                return false;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Validates that the given value is a MAC address.
     * Supported formats:
     * - 00:1A:2B:3C:4D:5E
     * - 00-1A-2B-3C-4D-5E
     * - 001A.2B3C.4D5E
     * - 001A2B3C4D5E
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function mac_address(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data)) {
            return false;
        }

        $patterns = [
            '/^([0-9A-Fa-f]{2}[:\-]){5}[0-9A-Fa-f]{2}$/',
            '/^([0-9A-Fa-f]{4}\.){2}[0-9A-Fa-f]{4}$/',
            '/^[0-9A-Fa-f]{12}$/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $data) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validates that the given value is a valid domain name (hostname).
     * Note: This validates the syntax of a domain (not a URL and not DNS existence).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function domain(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data)) {
            return false;
        }

        return filter_var($data, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false;
    }

    /**
     * Validates a SWIFT/BIC code (8 or 11 characters).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function bic(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data)) {
            return false;
        }

        $bic = strtoupper(str_replace(' ', '', $data));

        return preg_match('/^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$/', $bic) === 1;
    }

    /**
     * Validates a CIDR notation address (IPv4 or IPv6 with prefix length).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function cidr(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) || !str_contains($data, '/')) {
            return false;
        }

        [$ip, $prefix] = explode('/', $data, 2);

        if (!ctype_digit($prefix)) {
            return false;
        }

        $prefixLength = (int) $prefix;

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $prefixLength >= 0 && $prefixLength <= 32;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $prefixLength >= 0 && $prefixLength <= 128;
        }

        return false;
    }

    /**
     * Validates that an array contains all of the given keys.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Comma-separated list of required keys
     * @param array $dataToValidate
     * @return bool
     */
    public static function required_array_keys(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_array($data)) {
            return false;
        }

        $requiredKeys = array_filter(array_map('trim', explode(',', (string) $value)), static fn ($item) => $item !== '');

        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates that a date of birth yields an age within the given inclusive range.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Format: "min,max" (e.g. "18,65")
     * @param array $dataToValidate
     * @return bool
     */
    public static function age_between(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_numeric($data)) {
            return false;
        }

        $parts = array_map('trim', explode(',', (string) $value));
        if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
            return false;
        }

        try {
            $birthDate = new \DateTimeImmutable((string) $data);
        } catch (\Exception) {
            return false;
        }

        $age = $birthDate->diff(new \DateTimeImmutable('today'))->y;
        $min = (int) $parts[0];
        $max = (int) $parts[1];

        return $age >= $min && $age <= $max;
    }

    /**
     * Validates an EAN-8 or EAN-13 barcode (including checksum).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function ean(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_int($data)) {
            return false;
        }

        $ean = preg_replace('/\s+/', '', (string) $data);
        $length = strlen($ean);

        if (!ctype_digit($ean) || ($length !== 8 && $length !== 13)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < $length - 1; $i++) {
            $digit = (int) $ean[$i];
            $weight = (($length - 1 - $i) % 2 === 0) ? 1 : 3;
            $sum += $digit * $weight;
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $checkDigit === (int) $ean[$length - 1];
    }

    /**
     * Validates a locale / language tag (e.g. en, en_US, pt-BR).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function locale(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data)) {
            return false;
        }

        return preg_match('/^[a-z]{2,3}([_-][A-Za-z]{2})?$/', $data) === 1;
    }

    /**
     * Validates that the value is a declined boolean-like value (no, off, 0, false).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function declined(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return false;
        }

        if (is_bool($data)) {
            return $data === false;
        }

        if (is_int($data) || is_float($data)) {
            return (int) $data === 0;
        }

        if (!is_string($data)) {
            return false;
        }

        return in_array(strtolower(trim($data)), ['no', 'off', '0', 'false'], true);
    }

    /**
     * Validates an ISO 8601 duration (e.g. PT15M, P1DT2H).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function duration(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data)) {
            return false;
        }

        return preg_match(
            '/^P(?!$)(\d+Y)?(\d+M)?(\d+W)?(\d+D)?(T(?=\d)(\d+H)?(\d+M)?(\d+S)?)?$/',
            $data
        ) === 1;
    }

    /**
     * Validates a 5-field cron expression.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function cron(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data)) {
            return false;
        }

        $fields = preg_split('/\s+/', trim($data));
        if ($fields === false || count($fields) !== 5) {
            return false;
        }

        $patterns = [
            '(?:\*(?:\/\d+)?|\d+(?:-\d+)?(?:\/\d+)?(?:,\d+(?:-\d+)?(?:\/\d+)?)*)', // minute
            '(?:\*(?:\/\d+)?|\d+(?:-\d+)?(?:\/\d+)?(?:,\d+(?:-\d+)?(?:\/\d+)?)*)', // hour
            '(?:\*(?:\/\d+)?|\d+(?:-\d+)?(?:\/\d+)?(?:,\d+(?:-\d+)?(?:\/\d+)?)*)', // day of month
            '(?:\*(?:\/\d+)?|\d+(?:-\d+)?(?:\/\d+)?(?:,\d+(?:-\d+)?(?:\/\d+)?)*)', // month
            '(?:\*(?:\/\d+)?|\d+(?:-\d+)?(?:\/\d+)?(?:,\d+(?:-\d+)?(?:\/\d+)?)*)', // day of week
        ];

        $ranges = [
            [0, 59],
            [0, 23],
            [1, 31],
            [1, 12],
            [0, 7],
        ];

        foreach ($fields as $index => $field) {
            if (preg_match('/^' . $patterns[$index] . '$/', $field) !== 1) {
                return false;
            }

            if (!self::cronFieldWithinRange($field, $ranges[$index][0], $ranges[$index][1])) {
                return false;
            }
        }

        return true;
    }

    private static function cronFieldWithinRange(string $field, int $min, int $max): bool
    {
        foreach (explode(',', $field) as $part) {
            $stepParts = explode('/', $part, 2);
            $range = $stepParts[0];
            $step = $stepParts[1] ?? null;

            if ($step !== null && (!ctype_digit($step) || (int) $step < 1)) {
                return false;
            }

            if ($range === '*') {
                continue;
            }

            $bounds = explode('-', $range, 2);
            foreach ($bounds as $bound) {
                if (!ctype_digit($bound)) {
                    return false;
                }
                $number = (int) $bound;
                if ($number < $min || $number > $max) {
                    return false;
                }
            }

            if (count($bounds) === 2 && (int) $bounds[0] > (int) $bounds[1]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates that the data is numeric with at most the given number of decimal places.
     * Usage: decimal or decimal:2 (default 2). With decimal:2,2 both min and max fraction digits.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function decimal(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_numeric($data)) {
            return false;
        }

        [$minDecimals, $maxDecimals] = self::parseDecimalBounds($value);

        return self::decimalPlacesInRange((string) $data, $minDecimals, $maxDecimals);
    }

    /**
     * Validates a monetary amount: numeric, non-negative, with limited decimal places.
     * Usage: money or money:2
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Max decimal places (default 2)
     * @param array $dataToValidate
     * @return bool
     */
    public static function money(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_numeric($data) || (float) $data < 0) {
            return false;
        }

        $decimals = ($value === null || $value === '') ? 2 : (int) $value;

        return self::decimalPlacesInRange((string) $data, 0, $decimals);
    }

    /**
     * Validates that the data is a number greater than zero.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function positive(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        return is_numeric($data) && (float) $data > 0;
    }

    /**
     * Validates an ISO 4217 currency code.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function currency(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_numeric($data)) {
            return false;
        }

        $code = strtoupper(trim((string) $data));

        return isset(self::iso4217Currencies()[$code]);
    }

    /**
     * Validates that a numeric amount is between two inclusive bounds.
     * Usage: amount_between:0.01,9999.99
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function amount_between(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_numeric($data)) {
            return false;
        }

        $parts = array_map('trim', explode(',', (string) $value));
        if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
            return false;
        }

        $amount = (float) $data;
        $min = (float) $parts[0];
        $max = (float) $parts[1];

        return $amount >= $min && $amount <= $max;
    }

    /**
     * Validates a credit/debit card number using the Luhn checksum.
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function credit_card(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_int($data)) {
            return false;
        }

        $number = preg_replace('/[\s-]+/', '', (string) $data);
        if ($number === null || !ctype_digit($number)) {
            return false;
        }

        $length = strlen($number);
        if ($length < 13 || $length > 19) {
            return false;
        }

        return self::luhnCheck($number);
    }

    /**
     * Validates that a card number matches one of the given brands.
     * Usage: card_brand:visa,mastercard,amex,discover
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Comma-separated brand list
     * @param array $dataToValidate
     * @return bool
     */
    public static function card_brand(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_int($data)) {
            return false;
        }

        $number = preg_replace('/[\s-]+/', '', (string) $data);
        if ($number === null || !ctype_digit($number)) {
            return false;
        }

        $allowed = [];
        foreach (explode(',', (string) $value) as $brand) {
            $brand = strtolower(trim($brand));
            if ($brand !== '') {
                $allowed[] = $brand;
            }
        }

        if ($allowed === []) {
            $allowed = ['visa', 'mastercard', 'amex', 'discover'];
        }

        $detected = self::detectCardBrand($number);

        return $detected !== null && in_array($detected, $allowed, true);
    }

    /**
     * Validates a card CVV/CVC code.
     * Usage: cvv (3 or 4 digits) or cvv:3 / cvv:4
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function cvv(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_int($data)) {
            return false;
        }

        $cvv = (string) $data;
        if (!ctype_digit($cvv)) {
            return false;
        }

        if ($value === null || $value === '') {
            return in_array(strlen($cvv), [3, 4], true);
        }

        return strlen($cvv) === (int) $value;
    }

    /**
     * Validates a card expiry date that is not in the past.
     * Usage: expiry_date or expiry_date:m/y (also supports m/Y, ym, Y-m)
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Format string
     * @param array $dataToValidate
     * @return bool
     */
    public static function expiry_date(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_numeric($data)) {
            return false;
        }

        $format = ($value === null || $value === '') ? 'm/y' : (string) $value;
        $raw = trim((string) $data);

        $date = \DateTimeImmutable::createFromFormat('!' . $format, $raw);
        $errors = \DateTimeImmutable::getLastErrors();
        if ($date === false || ($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0) {
            return false;
        }

        $month = (int) $date->format('n');
        if ($month < 1 || $month > 12) {
            return false;
        }

        $expiryEnd = $date->modify('last day of this month')->setTime(23, 59, 59);
        $now = new \DateTimeImmutable('now');

        return $expiryEnd >= $now;
    }

    /**
     * Validates a domestic-style account number (digits only).
     * Usage: account_number or account_number:8,17
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Optional min,max length
     * @param array $dataToValidate
     * @return bool
     */
    public static function account_number(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_int($data)) {
            return false;
        }

        $number = preg_replace('/[\s-]+/', '', (string) $data);
        if ($number === null || !ctype_digit($number)) {
            return false;
        }

        $length = strlen($number);
        $min = 4;
        $max = 34;

        if ($value !== null && $value !== '') {
            $parts = array_map('trim', explode(',', (string) $value));
            if (count($parts) === 1 && ctype_digit($parts[0])) {
                $min = (int) $parts[0];
                $max = $min;
            } elseif (count($parts) === 2 && ctype_digit($parts[0]) && ctype_digit($parts[1])) {
                $min = (int) $parts[0];
                $max = (int) $parts[1];
            } else {
                return false;
            }
        }

        return $length >= $min && $length <= $max;
    }

    /**
     * Validates a VAT / tax identification number.
     * Usage: vat or vat:EU or vat:RO (country/region filter)
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function vat(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_string($data) && !is_numeric($data)) {
            return false;
        }

        $vat = strtoupper(preg_replace('/[\s.-]+/', '', (string) $data) ?? '');
        if ($vat === '') {
            return false;
        }

        $filter = strtoupper(trim((string) $value));
        $lengths = self::euVatLengths();

        if ($filter === '' || $filter === 'EU') {
            if (!preg_match('/^([A-Z]{2})([A-Z0-9]{2,12})$/', $vat, $matches)) {
                return false;
            }
            $country = $matches[1];
            $number = $matches[2];

            return isset($lengths[$country]) && in_array(strlen($number), (array) $lengths[$country], true);
        }

        if (isset($lengths[$filter])) {
            if (str_starts_with($vat, $filter)) {
                $number = substr($vat, 2);
            } else {
                $number = $vat;
            }

            return ctype_alnum($number) && in_array(strlen($number), (array) $lengths[$filter], true);
        }

        return preg_match('/^[A-Z0-9]{8,14}$/', $vat) === 1;
    }

    /**
     * Validates a percentage value between 0 and 100 (inclusive).
     * Usage: percentage or percentage:2 (max decimal places)
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value Optional max decimal places
     * @param array $dataToValidate
     * @return bool
     */
    public static function percentage(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (!is_numeric($data)) {
            return false;
        }

        $amount = (float) $data;
        if ($amount < 0 || $amount > 100) {
            return false;
        }

        if ($value === null || $value === '') {
            return true;
        }

        return self::decimalPlacesInRange((string) $data, 0, (int) $value);
    }

    /**
     * Validates a minor-units amount (non-negative integer, e.g. cents).
     *
     * @param mixed $data
     * @param string|null $key
     * @param mixed|null $value
     * @param array $dataToValidate
     * @return bool
     */
    public static function minor_units(mixed $data, ?string $key = null, mixed $value = null, array $dataToValidate = []): bool
    {
        if (is_null($data)) {
            return true;
        }

        if (is_int($data)) {
            return $data >= 0;
        }

        if (is_string($data) && ctype_digit($data)) {
            return true;
        }

        return false;
    }

    private static function parseDecimalBounds(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [0, 2];
        }

        $parts = array_map('trim', explode(',', (string) $value));
        if (count($parts) === 1) {
            $max = (int) $parts[0];

            return [0, $max];
        }

        return [(int) $parts[0], (int) $parts[1]];
    }

    private static function decimalPlacesInRange(string $number, int $minDecimals, int $maxDecimals): bool
    {
        if (!is_numeric($number) || stripos($number, 'e') !== false) {
            return false;
        }

        $normalized = ltrim($number, '+');
        if (!str_contains($normalized, '.')) {
            $decimals = 0;
        } else {
            $decimals = strlen(substr($normalized, strrpos($normalized, '.') + 1));
        }

        return $decimals >= $minDecimals && $decimals <= $maxDecimals;
    }

    private static function luhnCheck(string $number): bool
    {
        $sum = 0;
        $alt = false;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];
            if ($alt) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
            $alt = !$alt;
        }

        return $sum % 10 === 0;
    }

    private static function detectCardBrand(string $number): ?string
    {
        if (preg_match('/^4[0-9]{12}(?:[0-9]{3,6})?$/', $number)) {
            return 'visa';
        }

        if (
            preg_match('/^5[1-5][0-9]{14}$/', $number)
            || preg_match('/^2(2[2-9][0-9]|[3-6][0-9]{2}|7[01][0-9]|720)[0-9]{12}$/', $number)
        ) {
            return 'mastercard';
        }

        if (preg_match('/^3[47][0-9]{13}$/', $number)) {
            return 'amex';
        }

        if (
            preg_match('/^6011[0-9]{12}$/', $number)
            || preg_match('/^65[0-9]{14}$/', $number)
            || preg_match('/^64[4-9][0-9]{13}$/', $number)
        ) {
            return 'discover';
        }

        return null;
    }

    private static function iso4217Currencies(): array
    {
        static $currencies = null;

        if ($currencies !== null) {
            return $currencies;
        }

        $codes = [
            'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN',
            'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BRL',
            'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY',
            'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP',
            'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS', 'GIP', 'GMD',
            'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS',
            'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR',
            'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD',
            'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRU',
            'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK',
            'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG',
            'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK',
            'SGD', 'SHP', 'SLE', 'SOS', 'SRD', 'SSP', 'STN', 'SVC', 'SYP', 'SZL',
            'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH',
            'UGX', 'USD', 'UYU', 'UZS', 'VES', 'VND', 'VUV', 'WST', 'XAF', 'XCD',
            'XOF', 'XPF', 'YER', 'ZAR', 'ZMW', 'ZWL',
        ];

        $currencies = array_fill_keys($codes, true);

        return $currencies;
    }

    private static function euVatLengths(): array
    {
        return [
            'AT' => 9, 'BE' => [9, 10], 'BG' => [9, 10], 'CY' => 9, 'CZ' => [8, 9, 10],
            'DE' => 9, 'DK' => 8, 'EE' => 9, 'EL' => 9, 'GR' => 9, 'ES' => 9, 'FI' => 8,
            'FR' => 11, 'HR' => 11, 'HU' => 8, 'IE' => [8, 9], 'IT' => 11, 'LT' => [9, 12],
            'LU' => 8, 'LV' => 11, 'MT' => 8, 'NL' => 12, 'PL' => 10, 'PT' => 9,
            'RO' => [2, 3, 4, 5, 6, 7, 8, 9, 10], 'SE' => 12, 'SI' => 8, 'SK' => 10,
            'XI' => [5, 9, 12],
        ];
    }
}
