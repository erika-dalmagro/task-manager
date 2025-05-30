<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository
{
    public function getPaginatedTasks(array $filters): LengthAwarePaginator
    {
        $query = Task::query()->with('categories'); // Eager load

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by priority
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        // Filter by categories (many-to-many)
        if (!empty($filters['category_ids']) && is_array($filters['category_ids'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->whereIn('categories.id', $filters['category_ids']);
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = strtolower($filters['sort_dir'] ?? 'desc');

        if (!in_array($sortBy, ['title', 'priority', 'status', 'due_date', 'created_at'])) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        return $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(perPage: $filters['per_page'] ?? 5)
            ->appends($filters);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function findOrFail(int $id): Task
    {
        return Task::with('categories')->findOrFail($id);
    }
}
