<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key
        DB::statement('ALTER TABLE category_task DISABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE tasks DISABLE TRIGGER ALL;');
        
        DB::table('category_task')->truncate();
        Task::truncate();

        // Enable foreign key
        DB::statement('ALTER TABLE category_task ENABLE TRIGGER ALL;');
        DB::statement('ALTER TABLE tasks ENABLE TRIGGER ALL;');
        
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please seed categories first or ensure CategorySeeder runs before TaskSeeder.');
            return;
        }

        $workCategory = Category::where('name', 'Work')->first();
        $projectsCategory = Category::where('name', 'Projects')->first();
        $meetingsCategory = Category::where('name', 'Meetings')->first();
        $personalCategory = Category::where('name', 'Personal')->first();
        $errandsCategory = Category::where('name', 'Errands')->first();
        $groceriesCategory = Category::where('name', 'Groceries')->first();
        $healthCategory = Category::where('name', 'Health & Fitness')->first();


        $tasksData = [
            [
                'title' => 'Prepare Q3 Report',
                'description' => 'Gather all sales data and compile the quarterly report for management.',
                'status' => 'in_progress',
                'priority' => 'high',
                'categories' => [$workCategory->id, $projectsCategory->id]
            ],
            [
                'title' => 'Client Follow-up Calls',
                'description' => 'Call key clients to discuss ongoing projects and satisfaction.',
                'status' => 'pending',
                'priority' => 'medium',
                'categories' => [$workCategory->id, $meetingsCategory->id]
            ],
            [
                'title' => 'Book Doctor Appointment',
                'description' => 'Schedule annual check-up.',
                'status' => 'pending',
                'priority' => 'high',
                'categories' => [$personalCategory->id, $healthCategory->id]
            ],
            [
                'title' => 'Grocery Shopping',
                'description' => 'Buy milk, eggs, bread, and cheese.',
                'status' => 'completed',
                'priority' => 'low',
                'categories' => [$shoppingCategory->id ?? $categories->random()->id, $groceriesCategory->id ?? $categories->random()->id] // Fallback
            ],
            [
                'title' => 'Pay Utility Bills',
                'description' => 'Electricity, water, and internet bills due this week.',
                'status' => 'pending',
                'priority' => 'medium',
                'categories' => [$personalCategory->id, $errandsCategory->id]
            ],
            [
                'title' => 'Gym Session - Cardio',
                'description' => '30 minutes on the treadmill, 20 minutes on the elliptical.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'categories' => [$personalCategory->id, $healthCategory->id]
            ],
            [
                'title' => 'Plan Weekend Getaway',
                'description' => 'Research destinations and book accommodation.',
                'status' => 'pending',
                'priority' => 'low',
                'categories' => [$personalCategory->id]
            ],
             [
                'title' => 'Submit Project Alpha Deliverables',
                'description' => 'Finalize and submit all components for Project Alpha.',
                'status' => 'completed',
                'priority' => 'high',
                'categories' => [$workCategory->id, $projectsCategory->id]
            ],
            [
                'title' => 'Team Brainstorming Session',
                'description' => 'Discuss new ideas for the upcoming marketing campaign.',
                'status' => 'pending',
                'priority' => 'medium',
                'categories' => [$workCategory->id, $meetingsCategory->id]
            ],
        ];

        foreach ($tasksData as $taskItem) {
            $task = Task::create([
                'title' => $taskItem['title'],
                'description' => $taskItem['description'],
                'status' => $taskItem['status'],
                'priority' => $taskItem['priority'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $task->categories()->attach(collect($taskItem['categories'])->filter()->all());
        }

        // Some more tasks
        Task::factory()->count(5)->create()->each(function ($task) use ($categories) {
            $task->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}