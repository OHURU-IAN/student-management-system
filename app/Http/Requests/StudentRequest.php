<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('students')->ignore($this->route('student')),
            ],
            'address' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'regex:/^\+?[0-9 ]{7,20}$/'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'mobile.regex' => 'Enter a valid phone number (digits, spaces and an optional leading +).',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['course_id' => 'course'];
    }
}
