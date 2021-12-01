<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use Livewire\Livewire;
use App\Models\Category;
use App\Http\Livewire\CreateIdea;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateIdeaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_idea_form_does_not_show_when_logged_out()
    {
        $this->get(route('idea.index'))
            ->assertSuccessful()
            ->assertSee('Please login to create an idea.')
            ->assertDontSee('Let us know what you would like and we\'ll take a look over!');
    }

    /** @test */
    public function create_idea_form_does_show_when_logged_in()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('idea.index'))
            ->assertSuccessful()
            ->assertDontSee('Please login to create an idea.')
            ->assertSee('Let us know what you would like and we\'ll take a look over!', false);
    }

    /** @test */
    public function main_page_contains_create_idea_livewire_component()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('idea.index'))
            ->assertSeeLivewire('create-idea');
    }

    /** @test */
    public function create_idea_form_validation_works()
    {
        Livewire::actingAs(User::factory()->create())
            ->test(CreateIdea::class)
            ->set('title', '')
            ->set('category_id', '')
            ->set('description', '')
            ->call('createIdea')
            ->assertHasErrors(['title', 'category_id', 'description'])
            ->assertSee('The title field is required.');
    }

    /** @test */
    public function creating_an_idea_works_correctly()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create();
        Status::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title', 'My first idea')
            ->set('category_id', $categoryOne->id)
            ->set('description', 'This is my first idea!')
            ->call('createIdea')
            ->assertRedirect(route('idea.index'));

        $this->actingAs($user)->get(route('idea.index'))
            ->assertSuccessful()
            ->assertSee('My first idea')
            ->assertSee('This is my first idea!');

        $this->assertDatabaseHas('ideas', [
            'title' => 'My first idea',
        ]);

        $this->assertDatabaseHas('votes', [
            'idea_id' => 1,
            'user_id' => 1,
        ]);
    }

    /** @test */
    public function creating_two_ideas_with_same_title_still_works_but_has_different_slugs()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        Status::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title', 'My first idea')
            ->set('category_id', $categoryOne->id)
            ->set('description', 'This is my first idea!')
            ->call('createIdea')
            ->assertRedirect(route('idea.index'));

        $this->assertDatabaseHas('ideas', [
            'title' => 'My first idea',
            'slug' => 'my-first-idea'
        ]);

        Livewire::actingAs($user)
            ->test(CreateIdea::class)
            ->set('title', 'My first idea')
            ->set('category_id', $categoryOne->id)
            ->set('description', 'This is my first idea!')
            ->call('createIdea')
            ->assertRedirect(route('idea.index'));

        $this->assertDatabaseHas('ideas', [
            'title' => 'My first idea',
            'slug' => 'my-first-idea-2'
        ]);
    }
}
