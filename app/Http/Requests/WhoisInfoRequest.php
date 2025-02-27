<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhoisInfoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain' => ['required', 'string', 'regex:/^[^.]+\.[^.]+/']
        ];
    }

    public function messages(): array
    {
        return [
            'domain.required' => 'Domain is required',
            'domain.string' => 'Domain must be a string',
            'domain.regex' => 'Invalid domain format'
        ];
    }
}
