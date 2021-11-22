<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Category 1']);
        Category::create(['name' => 'Category 2']);
        Category::create(['name' => 'Category 3']);
        Category::create(['name' => 'Category 4']);

        Status::create(['name' => 'Open', 'classes' => 'bg-gray-200']);
        Status::create(['name' => 'Considering', 'classes' => 'bg-purple text-white']);
        Status::create(['name' => 'In Progress', 'classes' => 'bg-yellow text-white']);
        Status::create(['name' => 'Implemented', 'classes' => 'bg-green text-white']);
        Status::create(['name' => 'Closed', 'classes' => 'bg-red text-white']);

        Idea::factory(30)->create();
    }
}
