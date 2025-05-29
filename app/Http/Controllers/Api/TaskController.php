<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    public function index(Request $request)
    {
        $tasks = $this->service->list($request->only(['status', 'priority', 'sort_by', 'sort_dir', 'per_page']));
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->service->store($request->validated());
        return new TaskResource($task);

    }

    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $updatedTask = $this->service->update($task, $request->validated());
        return new TaskResource($updatedTask);
    }

    public function destroy(Task $task)
    {
        $this->service->delete($task);
        return response()->noContent();
    }
}

