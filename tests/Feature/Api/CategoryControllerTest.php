<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can retrieve a list of categories', function () {
    // Creating categories with parent and children
    $parentCategory = Category::factory()->create(['name' => 'Parent Category']);
    Category::factory()->create(['name' => 'Child Category 1', 'parent_id' => $parentCategory->id]);
    Category::factory()->create(['name' => 'Child Category 2', 'parent_id' => $parentCategory->id]);
    Category::factory()->create(['name' => 'StandAlone Category']);

    $response = $this->getJson('/api/categories');

    // Check
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'parent_id',
                'children' => [
                    '*' => [
                        'id', 'name', 'parent_id', 'created_at'
                    ]
                ],
                'created_at'
            ]
        ]
    ]);
    $response->assertJsonCount(2, 'data');
    $response->assertJsonFragment(['name' => 'Parent Category']);
    $response->assertJsonFragment(['name' => 'StandAlone Category']);
    $response->assertJsonFragment(['name' => 'Child Category 1']);
});

// Starting tests!
test('can create a category', function () {
    $categoryData = ['name' => 'New Category'];

    $response = $this->postJson('/api/categories', $categoryData);
    $response->assertCreated();
    $response->assertJsonFragment(['name' => 'New Category']);
    $this->assertDatabaseHas('categories', ['name' => 'New Category']);
});

test('can create a nested category', function () {
    $parentCategory = Category::factory()->create();
    
    $categoryData = [
        'name' => 'Nested Category',
        'parent_id' => $parentCategory->id
    ];

    $response = $this->postJson('/api/categories', $categoryData);
    $response->assertCreated();

    $this->assertDatabaseHas('categories', [
        'name' => 'Nested Category',
        'parent_id' => $parentCategory->id
    ]);
});

test('can show a specific category', function () {
    $category = Category::factory()->create();

    $response = $this->getJson("/api/categories/{$category->id}");
    $response->assertOk();
    $response->assertJsonFragment(['name' => $category->name]);
});

test('can update a category', function () {
    $category = Category::factory()->create();

    $updatedData = ['name' => 'Updated Category Name'];
    
    $response = $this->putJson("/api/categories/{$category->id}", $updatedData);
    $response->assertOk();
    $response->assertJsonFragment(['name' => 'Updated Category Name']);
    
    $this->assertDatabaseHas('categories', $updatedData);
});

test('can delete a category', function () {
    $category = Category::factory()->create();
    
    $response = $this->deleteJson("/api/categories/{$category->id}");

    // Assert: Check response + soft delete
    $response->assertNoContent();
    
    $this->assertSoftDeleted('categories', ['id' => $category->id]);
});

test('cannot create category with invalid data', function () {
    $invalidData = ['parent_id' => 1];
    
    $response = $this->postJson('/api/categories', $invalidData);

    // Check for errors
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name']);
});

test('cannot update category with invalid data', function () {
    $category = Category::factory()->create();
    
    $invalidData = ['name' => ''];
    
    $response = $this->putJson("/api/categories/{$category->id}", $invalidData);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name']);
});