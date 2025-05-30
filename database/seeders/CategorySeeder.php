<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key
        DB::statement('ALTER TABLE categories DISABLE TRIGGER ALL;');
     
        Category::truncate();

        $parentCategories = [
            ['name' => 'Work', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Personal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shopping', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Health & Fitness', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($parentCategories as $cat) {
            Category::create($cat);
        }

        $work = Category::where('name', 'Work')->first();
        $personal = Category::where('name', 'Personal')->first();
        $shopping = Category::where('name', 'Shopping')->first();

        $childCategories = [
            // Work Children
            ['name' => 'Meetings', 'parent_id' => $work->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Projects', 'parent_id' => $work->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reports', 'parent_id' => $work->id, 'created_at' => now(), 'updated_at' => now()],

            // Personal Children
            ['name' => 'Appointments', 'parent_id' => $personal->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Errands', 'parent_id' => $personal->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hobbies', 'parent_id' => $personal->id, 'created_at' => now(), 'updated_at' => now()],

            // Shopping Children
            ['name' => 'Groceries', 'parent_id' => $shopping->id, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Online Orders', 'parent_id' => $shopping->id, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($childCategories as $cat) {
            Category::create($cat);
        }
        
        // Re-enable foreign key
        DB::statement('ALTER TABLE categories ENABLE TRIGGER ALL;');
        
    }
}