<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class AssignCategoryToTasksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'task_ids' => ['required', 'array'],
            'task_ids.*' => ['integer', 'exists:tasks,id'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ];
    }
}
