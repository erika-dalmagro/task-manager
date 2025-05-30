<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(protected CategoryRepository $repository) {}

    public function list()
    {
        return $this->repository->all();
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function getAllNested()
    {
        return $this->repository->all();
    }

    public function update(Category $category, array $data): Category
    {
        return $this->repository->update($category, $data);
    }
    
    public function delete(Category $category): void
    {
        $this->repository->delete($category);
    }
}
