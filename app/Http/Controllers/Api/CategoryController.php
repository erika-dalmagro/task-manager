<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service) {}

    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return CategoryResource::collection($categories);
    }

    public function allCategories()
    {
        // Fetch all categories
        $categories = Category::with('parent')->orderBy('name')->get();

        return response()->json($categories->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'parent_id' => $category->parent_id
            ];
        }));
    }
    
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->store($request->validated());
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->service->update($category, $request->validated());
        return new CategoryResource($updatedCategory);
    }
    
    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return response()->noContent();
    }
}
