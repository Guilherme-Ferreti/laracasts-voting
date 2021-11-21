<?php

namespace Database\Seeders;

use App\Models\Idea;
use App\Models\Category;
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

        Idea::factory(30)->create();
    }
}
