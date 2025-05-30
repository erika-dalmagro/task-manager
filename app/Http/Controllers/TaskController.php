<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
       public function index()
    {
        return Inertia::render('Tasks/Index');
        
    }

    public function create()
    {
        return Inertia::render('Tasks/Form');
    }

    public function edit(Task $task)
    {
        $task->load('categories');

        return Inertia::render('Tasks/Form', [
            'task' => new TaskResource($task),
        ]);
    }
}
