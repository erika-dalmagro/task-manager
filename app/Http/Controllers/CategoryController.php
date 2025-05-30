<?php 

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return Inertia::render('Categories/Index', [
            'categories' => CategoryResource::collection($categories),
        ]);
    }

    public function create()
    {
        return Inertia::render('Categories/Form');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Categories/Form', [
            'category' => new CategoryResource($category),
        ]);
    }
}
