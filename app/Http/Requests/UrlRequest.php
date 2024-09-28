<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UrlRequest extends FormRequest
{
    public static function validate(array $data)
    {
        // Define rules
        $rules = [
            'original_url' => 'required|url',
        ];

        // Validate the data
        $validator = Validator::make($data, $rules);

        // If validation fails, throw an exception
        if ($validator->fails()) {
            $errors = implode("\n", $validator->errors()->all());
            throw new \Exception('Validation failed: ' . "\n" . $errors);
        }

        // Return the validated data
        return $validator->validated();
    }
}
