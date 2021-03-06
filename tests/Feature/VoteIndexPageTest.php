<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;
use App\Http\Livewire\IdeaIndex;
use App\Http\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteIndexPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_contains_idea_index_livewire_component()
    {
        Idea::factory()->create();

        $this->get(route('idea.index'))
            ->assertSeeLivewire('idea-index');
    }

    /** @test */
    public function ideas_index_livewire_component_correctly_receives_votes_count()
    {
        $idea = Idea::factory()->create();

        Vote::factory(2)->create([
            'idea_id' => $idea->id,
        ]);

        Livewire::test(IdeasIndex::class)
            ->assertViewHas('ideas', fn ($ideas) => $ideas->first()->votes_count == 2);
    }

    /** @test */
    public function votes_count_shows_correctly_on_index_page_livewire_component()
    {
        $idea = Idea::factory()->create();

        $idea->votes_count = 5;

        Livewire::test(IdeaIndex::class, ['idea' => $idea])
            ->assertSet('votesCount', 5);
    }

    /** @test */
    public function user_who_is_logged_in_shows_voted_if_idea_already_voted_for()
    {
        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $idea->votes_count = 1;
        $idea->voted_by_user = 1;

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, ['idea' => $idea])
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');
    }

    /** @test */
    public function user_who_is_logged_in_can_vote_for_idea()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);

        $idea->votes_count = 5;

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, ['idea' => $idea])
            ->call('vote')
            ->assertSet('votesCount', 6)
            ->assertSet('hasVoted', true)
            ->assertSee('Voted');
    
        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);
    }
    
    /** @test */
    public function user_who_is_logged_in_can_remove_vote_for_idea()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $idea->votes_count = 5;
        $idea->voted_by_user = 1;

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, ['idea' => $idea])
            ->call('vote')
            ->assertSet('votesCount', 4)
            ->assertSet('hasVoted', false)
            ->assertSee('Vote')
            ->assertDontSee('Voted');

        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);
    }
}
