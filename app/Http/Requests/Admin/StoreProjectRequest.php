<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'name_ar' => ['nullable', 'required_without:name_en', 'string', 'max:200'],
            'name_en' => ['nullable', 'required_without:name_ar', 'string', 'max:200'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:200', 'unique:projects,slug'],
            'description_ar' => ['nullable', 'string', 'max:5000'],
            'description_en' => ['nullable', 'string', 'max:5000'],
            'project_type' => ['nullable', 'string', 'max:60'],
            'system_type' => ['nullable', 'string', 'max:60'],
            'client' => ['nullable', 'string', 'max:200'],
            'target_audience_ar' => ['nullable', 'string', 'max:1000'],
            'target_audience_en' => ['nullable', 'string', 'max:1000'],
            'technologies' => ['nullable', 'string', 'max:1000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'screenshots' => ['nullable', 'array', 'max:12'],
            'screenshots.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'website_url' => ['nullable', 'url:http,https', 'max:255'],
            'repository_url' => ['nullable', 'url:http,https', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_visible' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
        ];
    }
}
