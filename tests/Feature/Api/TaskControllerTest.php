<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses( RefreshDatabase::class);

test('can retrieve a list of tasks', function () {
    // Create tasks
    Task::factory()->count(3)->create();

    $response = $this->getJson('/api/tasks');

    // Check
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'title',
                'description',
                'status',
                'priority',
                'created_at',
                'updated_at',
                'categories',
                'category_ids'
            ]
        ],
        'meta' => [
            'current_page',
            'last_page',
            'per_page',
            'total'
        ]
    ]);
    $response->assertJsonCount(3, 'data');
});

test('can filter tasks by status', function () {
    // Create tasks
    Task::factory()->create(['status' => 'pending']);
    Task::factory()->create(['status' => 'in_progress']);
    Task::factory()->create(['status' => 'completed']);

    $response = $this->getJson('/api/tasks?status=pending');

    // Only 'pending' tasks are returned
    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['status' => 'pending']);
    $response->assertJsonMissing(['status' => 'in_progress']);
});

test('can filter tasks by priority', function () {
    // Same logic as before!
    Task::factory()->create(['priority' => 'low']);
    Task::factory()->create(['priority' => 'medium']);
    Task::factory()->create(['priority' => 'high']);

    $response = $this->getJson('/api/tasks?priority=high');
    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['priority' => 'high']);
    $response->assertJsonMissing(['priority' => 'low']);
});

test('can sort tasks by title in ascending order', function () {
    Task::factory()->create(['title' => 'Z Task']);
    Task::factory()->create(['title' => 'A Task']);
    Task::factory()->create(['title' => 'M Task']);

    $response = $this->getJson('/api/tasks?sort_by=title&sort_dir=asc');
    $response->assertOk();
    $response->assertJsonPath('data.0.title', 'A Task');
    $response->assertJsonPath('data.1.title', 'M Task');
    $response->assertJsonPath('data.2.title', 'Z Task');
});

test('can create a task', function () {
    $taskData = [
        'title' => 'New Task',
        'description' => 'This is a new task description.',
        'status' => 'pending',
        'priority' => 'low'
    ];

    $response = $this->postJson('/api/tasks', $taskData);
    $response->assertCreated();
    $response->assertJsonFragment(['title' => 'New Task']);
    $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
});

test('can create a task with categories', function () {
    // Create categories
    $category1 = Category::factory()->create();
    $category2 = Category::factory()->create();

    // Create task + associations
    $taskData = [
        'title' => 'Task with Categories',
        'description' => '...',
        'status' => 'pending',
        'priority' => 'medium',
        'category_ids' => [$category1->id, $category2->id]
    ];

    $response = $this->postJson('/api/tasks', $taskData);
    $response->assertCreated();
    $response->assertJsonFragment(['title' => 'Task with Categories']);
    $response->assertJsonCount(2, 'data.categories');
    $this->assertDatabaseHas('category_task', [
        'task_id' => $response->json('data.id'),
        'category_id' => $category1->id
    ]);
    $this->assertDatabaseHas('category_task', [
        'task_id' => $response->json('data.id'),
        'category_id' => $category2->id
    ]);
});

test('can show a specific task', function () {
    $task = Task::factory()->create();
    $category = Category::factory()->create();
    $task->categories()->attach($category);

    $response = $this->getJson("/api/tasks/{$task->id}");
    $response->assertOk();
    $response->assertJsonFragment(['title' => $task->title]);
    $response->assertJsonCount(1, 'data.categories');
    $response->assertJsonFragment(['name' => $category->name]);
});

test('can update a task', function () {
    $task = Task::factory()->create();
    $updatedData = [
        'title' => 'Updated Task Title',
        'status' => 'completed',
        'priority' => 'high'
    ];

    $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);
    $response->assertOk();
    $response->assertJsonFragment(['title' => 'Updated Task Title']);
    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Updated Task Title', 'status' => 'completed', 'priority' => 'high']);
});

test('can update a task and sync categories', function () {
    $task = Task::factory()->create();
    $category1 = Category::factory()->create();
    $category2 = Category::factory()->create();
    $category3 = Category::factory()->create();

    $task->categories()->attach($category1->id);

    $updatedData = [
        'title' => 'Task with Updated Categories',
        'category_ids' => [$category2->id, $category3->id]
    ];

    $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);
    $response->assertOk();
    $response->assertJsonFragment(['title' => 'Task with Updated Categories']);
    $response->assertJsonCount(2, 'data.categories'); // Should have 2 categories now
    $this->assertDatabaseMissing('category_task', [
        'task_id' => $task->id,
        'category_id' => $category1->id
    ]);
    $this->assertDatabaseHas('category_task', [
        'task_id' => $task->id,
        'category_id' => $category2->id
    ]);
    $this->assertDatabaseHas('category_task', [
        'task_id' => $task->id,
        'category_id' => $category3->id
    ]);
});

test('can delete a task', function () {
    $task = Task::factory()->create();
    $category = Category::factory()->create();
    $task->categories()->attach($category);

    $response = $this->deleteJson("/api/tasks/{$task->id}");
    $response->assertNoContent();

    $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    $this->assertDatabaseMissing('category_task', [
        'task_id' => $task->id,
        'category_id' => $category->id
    ]);
});

test('cannot create task with invalid data', function () {
    // Invalid data (missing title, invalid status)
    $invalidData = [
        'description' => 'Some description',
        'status' => 'invalid_status',
        'priority' => 'low'
    ];

    $response = $this->postJson('/api/tasks', $invalidData);

    // Check for errors
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['title', 'status']);
});

test('cannot update task with invalid data', function () {
    $task = Task::factory()->create();
    $invalidData = [
        'status' => 'non-existent-status',
        'category_ids' => [999] // non-existent category
    ];

    $response = $this->putJson("/api/tasks/{$task->id}", $invalidData);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['status', 'category_ids.0']);
});