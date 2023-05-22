<?php

namespace App\Http\Requests;

use App\Enums\Units;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0|max:100000000',
            'amount' => 'required|integer|min:0|max:100000000',
            'unit' => 'required|in:' . join(',', Units::values()),
        ];
    }
}
