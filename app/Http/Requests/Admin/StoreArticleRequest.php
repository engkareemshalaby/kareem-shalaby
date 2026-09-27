<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'distinct', 'exists:tags,id'],
            'new_tag_name' => ['nullable', 'required_with:new_tag_slug', 'string', 'max:60', 'unique:tags,name'],
            'new_tag_slug' => ['nullable', 'required_with:new_tag_name', 'alpha_dash:ascii', 'max:60', 'unique:tags,slug'],
            'language' => ['required', 'in:ar,en,both'],
            'title_ar' => ['nullable', 'required_if:language,ar,both', 'string', 'max:255'],
            'title_en' => ['nullable', 'required_if:language,en,both', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash:ascii', 'max:255', 'unique:articles,slug'],
            'excerpt_ar' => ['nullable', 'string', 'max:500'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'body_ar' => ['nullable', 'required_if:language,ar,both', 'string'],
            'body_en' => ['nullable', 'required_if:language,en,both', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['sometimes', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:70'],
            'seo_description' => ['nullable', 'string', 'max:170'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
