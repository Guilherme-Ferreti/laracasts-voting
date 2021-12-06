<?php

namespace Tests\Unit;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IdeaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_check_idea_is_voted_for_by_user()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        
        $statusOpen = Status::create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description for my idea',
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($idea->isVotedByUser($user));
        $this->assertFalse($idea->isVotedByUser($userB));
        $this->assertFalse($idea->isVotedByUser(null));
    }

    /** @test */
    public function user_can_vote_for_idea()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        
        $statusOpen = Status::create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description for my idea',
        ]);

        $this->assertFalse($idea->isVotedByUser($user));
        $idea->vote($user);
        $this->assertTrue($idea->isVotedByUser($user));
    }

    /** @test */
    public function votting_for_an_idea_thats_already_voted_for_throws_exception()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        
        $statusOpen = Status::create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description for my idea',
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $this->expectException(DuplicateVoteException::class);

        $idea->vote($user);
    }

    /** @test */
    public function removing_a_vote_that_doesnt_exist_throws_exception()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        
        $statusOpen = Status::create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description for my idea',
        ]);
        
        $this->expectException(VoteNotFoundException::class);

        $idea->removeVote($user);
    }

    /** @test */
    public function user_can_remove_vote_for_idea()
    {
        $user = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        
        $statusOpen = Status::create(['name' => 'Open']);

        $idea = Idea::factory()->create([
            'title' => 'My First Idea',
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'description' => 'Description for my idea',
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($idea->isVotedByUser($user));
        $idea->removeVote($user);
        $this->assertFalse($idea->isVotedByUser($user));
    }
}
