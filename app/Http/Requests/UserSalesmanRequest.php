<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserSalesmanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'data' => 'required|array',
            'data.name' => 'required|string|max:255',
            'data.email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user),
            ],
            'salesman' => 'required|array',
            'salesman.code' => 'required|string|max:255',
        ];

        if ($this->isMethod('POST')) {
            return array_merge($rules, [
                'data.password' => 'required|string|max:255|confirmed',
            ]);
        }

        return $rules;
    }
}
