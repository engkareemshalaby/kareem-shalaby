<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tags' => ['required', 'array', 'min:1', 'max:20'],
            'tags.*.name' => ['required', 'string', 'max:60', 'distinct:strict', 'unique:tags,name'],
            'tags.*.slug' => ['required', 'alpha_dash:ascii', 'max:60', 'distinct:strict', 'unique:tags,slug'],
        ];
    }
}
