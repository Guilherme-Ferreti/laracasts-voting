<?php

namespace Tests\Feature\Filters;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;
use App\Models\Comment;
use App\Models\Category;
use App\Http\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtherFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function top_voted_filter_works()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();
        $userC = User::factory()->create();
        
        $ideaOne = Idea::factory()->create();
        $ideaTwo = Idea::factory()->create();

        Vote::factory()->create([
            'idea_id' => $ideaOne->id,
            'user_id' => $user->id,
        ]);

        Vote::factory()->create([
            'idea_id' => $ideaOne->id,
            'user_id' => $userB->id,
        ]);

        Vote::factory()->create([
            'idea_id' => $ideaTwo->id,
            'user_id' => $userC->id,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'Top Voted')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2
                    && $ideas->first()->votes()->count() === 2
                    && $ideas->get(1)->votes()->count() === 1;
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_logged_in()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();
        
        Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
        ]);

        Idea::factory()->create([
            'title' => 'My Second Idea',
            'user_id' => $user->id,
        ]);

        Idea::factory()->create([
            'title' => 'My Third Idea',
            'user_id' => $userB->id,
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('filter', 'My Ideas')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2
                    && $ideas->first()->title === 'My Second Idea'
                    && $ideas->get(1)->title === 'My First Idea';
            });
    }

    /** @test */
    public function my_ideas_filter_works_correctly_when_user_is_not_logged_in()
    {
        $user = User::factory()->create();
        
        Idea::factory(2)->create([
            'user_id' => $user->id,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'My Ideas')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function my_ideas_filter_works_correctly_with_categories_filter()
    {
        $user = User::factory()->create();
        
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
        ]);

        Idea::factory()->create([
            'title' => 'My Second Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
        ]);

        Idea::factory()->create([
            'title' => 'My Third Idea',
            'user_id' => $user->id,
            'category_id' => $categoryTwo->id,
        ]);

        Livewire::actingAs($user)
            ->test(IdeasIndex::class)
            ->set('category', 'Category 1')
            ->set('filter', 'My Ideas')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2
                    && $ideas->first()->title === 'My Second Idea'
                    && $ideas->get(1)->title === 'My First Idea';
            });
    }

    /** @test */
    public function no_filters_works_correctly()
    {
        $user = User::factory()->create();

        Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
        ]);

        Idea::factory()->create([
            'title' => 'My Second Idea',
            'user_id' => $user->id,
        ]);

        Idea::factory()->create([
            'title' => 'My Third Idea',
            'user_id' => $user->id,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'No Filter')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 3
                    && $ideas->first()->title === 'My Third Idea'
                    && $ideas->get(1)->title === 'My Second Idea'
                    && $ideas->get(2)->title === 'My First Idea';
            });
    }

    /** @test */
    public function spam_ideas_filters_works_correctly()
    {
        Idea::factory(5)->create();

        Idea::factory()->create([
            'title' => 'My First Idea',
            'spam_reports' => 1,
        ]);

        Idea::factory()->create([
            'title' => 'My Second Idea',
            'spam_reports' => 2,
        ]);

        Idea::factory()->create([
            'title' => 'My Third Idea',
            'spam_reports' => 3,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'Spam Ideas')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 3
                    && $ideas->first()->title === 'My Third Idea'
                    && $ideas->get(1)->title === 'My Second Idea'
                    && $ideas->get(2)->title === 'My First Idea';
            });
    }

    /** @test */
    public function spam_comments_filter_works_correctly()
    {  
        Comment::factory(2)->create([
            'spam_reports' => 2
        ]);

        Comment::factory(5)->create([
            'spam_reports' => 0,
        ]);

        Livewire::test(IdeasIndex::class)
            ->set('filter', 'Spam Comments')
            ->assertViewHas('ideas', function ($ideas) {
                return $ideas->count() === 2;
            });
    }
}
