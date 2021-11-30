<?php

namespace Tests\Feature;

use App\Http\Livewire\SetStatus;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class AdminSetStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function show_page_contains_set_status_livewire_component_when_user_is_admin()
    {
        $user = User::factory()->create([
            'name' => 'Guilherme',
            'email' => 'guilherme@gmail.com',
        ]);
        
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description of my first idea',
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('set-status');
    }

    /** @test */
    public function show_page_does_not_contain_set_status_livewire_component_when_user_is_admin()
    {
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@gmail.com',
        ]);
        
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description of my first idea',
        ]);

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('set-status');
    }

    /** @test */
    public function initial_status_is_set_correctly()
    {
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@gmail.com',
        ]);
        
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'description' => 'Description of my first idea',
        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class, ['idea' => $idea])
            ->assertSet('status', $statusConsidering->id);
    }

    /** @test */
    public function can_set_status_correctly()
    {
        $user = User::factory()->create([
            'name' => 'Guilherme',
            'email' => 'guilherme@gmail.com',
        ]);
        
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
        $statusInProgress = Status::factory()->create(['id' => 3, 'name' => 'In Progress']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusConsidering->id,
            'description' => 'Description of my first idea',
        ]);

        Livewire::actingAs($user)
            ->test(SetStatus::class, ['idea' => $idea])
            ->set('status', $statusInProgress->id)
            ->call('setStatus')
            ->assertEmitted('statusWasUpdated');

        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'status_id' => $statusInProgress->id,
        ]);
    }
}
