<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Support\Arr;

class TaskService
{
    public function __construct(protected TaskRepository $repository) {}

    public function list(array $filters)
    {
        return $this->repository->getPaginatedTasks($filters);
    }

    public function store(array $data): Task
    {
        $categories = $data['category_ids'] ?? [];

        $task = $this->repository->create(Arr::except($data, ['category_ids']));
        $task->categories()->sync($categories);

        return $task->load('categories');
    }

    public function update(Task $task, array $data): Task
    {
        $categories = $data['category_ids'] ?? [];

        $this->repository->update($task, Arr::except($data, ['category_ids']));
        $task->categories()->sync($categories);

        return $task->load('categories');
    }

    public function delete(Task $task): void
    {   
        $task->categories()->detach();
        
        $this->repository->delete($task);
    }
}
