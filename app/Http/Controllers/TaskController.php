<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;

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
        return Inertia::render('Tasks/Form', [
            'task' => $task
        ]);
    }
}
