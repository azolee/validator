<?php

namespace Azolee\Validator;

use Azolee\Validator\Contracts\ValidationErrorBagInterface;
use Azolee\Validator\Helpers\ErrorsHelper;

class ValidationErrorBag implements ValidationErrorBagInterface
{
    private array $list = [
        ValidationRules::CUSTOM_RULE => 'The :attribute is invalid.',
        'numeric' => 'The :attribute is not a valid number.',
        'string' => 'The :attribute must be a string.',
        'boolean' => 'The :attribute must be a boolean.',
        'not_null' => 'The :attribute must not be null.',
        'array' => 'The :attribute must be an array.',
        'email' => 'The :attribute must be a valid email address.',
        'url' => 'The :attribute must be a valid URL.',
        'min' => 'The :attribute size must be at least :value.',
        'max' => 'The :attribute size must not be greater than :value.',
        'in' => 'The :attribute must be one of the following: :values.',
        'date' => 'The :attribute must be a valid date.',
        'alpha' => 'The :attribute may only contain letters.',
        'alpha_num' => 'The :attribute may only contain letters and numbers.',
        'digits' => 'The :attribute must be digits.',
        'digits_between' => 'The :attribute must be between :value and :value1 digits.',
        'different' => 'The :attribute and :value must be different.',
        'same' => 'The :attribute and :value must match.',
        'ip' => 'The :attribute must be a valid IP address.',
        'json' => 'The :attribute must be a valid JSON string.',
        'regex' => 'The :attribute format is invalid.',
        'required' => 'The :attribute field is required.',
        'contains' => 'The :attribute must contain :value.',
        'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes, and underscores.',
        'after' => 'The :attribute must be a date after :value.',
        'before' => 'The :attribute must be a date before :value.',
        'active_url' => 'The :attribute is not a valid URL.',
        'ascii' => 'The :attribute must be ASCII characters.',
        'date_equals' => 'The :attribute must be a date equal to :value.',
        'distinct' => 'The :attribute field has a duplicate value.',
        'date_format' => 'The :attribute does not match the format :value.',
        'password' => 'The :attribute must meet the password requirements.',
        'base64' => 'The :attribute must be a valid base64 string.',
        'base64_image' => 'The :attribute must be a valid base64 encoded image.',
        'present' => 'The :attribute field must be present.',
    ];

    /**
     * @return array
     * */
    public function getErrorFor(array|string $rules, string $attribute, array $extraParams = []): string
    {
        return ErrorsHelper::getError($this->list, $rules, $attribute, $extraParams);
    }
}