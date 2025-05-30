<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        $categoryIdToExclude = $this->category ? $this->category->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                // The parent_id cannot be the ID of the category itself.
                // This rule is applied only if $categoryIdToExclude is not null (which it should be in an update).
                Rule::when($categoryIdToExclude !== null, function () use ($categoryIdToExclude) {
                    return Rule::notIn([$categoryIdToExclude]);
                }),
            ],
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'parent_id.not_in' => 'A category cannot be its own parent.',
        ];
    }
}