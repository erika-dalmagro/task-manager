<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskService
{
    public function __construct(protected TaskRepository $repository) {}

    public function list(array $filters)
    {
        return $this->repository->getPaginatedTasks($filters);
    }

    public function store(array $data): Task
    {
        return $this->repository->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        return $this->repository->update($task, $data);
    }

    public function delete(Task $task): void
    {
        $this->repository->delete($task);
    }
}
