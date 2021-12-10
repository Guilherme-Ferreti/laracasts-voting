<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowCommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function idea_comments_livewire_component_renders()
    {
        Comment::factory(5)->for(Idea::factory())->create();

        $this->get(route('idea.show', Idea::first()))
            ->assertSeeLivewire('idea-comments');
    }
    
    /** @test */
    public function idea_comment_livewire_component_renders()
    {
        Comment::factory(5)->for(Idea::factory())->create();

        $this->get(route('idea.show', Idea::first()))
            ->assertSeeLivewire('idea-comment');
    }

    /** @test */
    public function no_comments_shows_appropriate_message()
    {
        $this->get(route('idea.show', Idea::factory()->create()))
            ->assertSee('No comments yet...');
    }

    /** @test */
    public function list_of_comments_shows_on_idea_page()
    {
        $comments = Comment::factory(2)->for(Idea::factory())->create();

        $this->get(route('idea.show', Idea::first()))
            ->assertSeeInOrder([
                $comments->first()->body, 
                $comments->get(1)->body
            ])
            ->assertSee('2 Comments');
    }

    /** @test */
    public function comments_count_shows_correctly_on_index_page()
    {
        Comment::factory(2)->for(Idea::factory())->create();

        $this->get(route('idea.index'))
            ->assertSee('2 Comments');
    }

    /** @test */
    public function op_badge_shows_if_author_of_idea_comments_on_idea()
    {
        $author = User::factory()->create();

        $idea = Idea::factory()->create([
            'user_id' => $author->id
        ]);

        Comment::factory()->create();

        Comment::factory()->create([
            'user_id' => $author->id,
            'idea_id' => $idea->id,
        ]);

        $this->get(route('idea.show', $idea))
            ->assertSee('OP');
    }

    /** @test */
    public function op_badge_does_not_show_if_author_of_idea_does_not_comment_on_idea()
    {
        $author = User::factory()->create();

        $idea = Idea::factory()->create([
            'user_id' => $author->id
        ]);

        Comment::factory(5)->create();

        $this->get(route('idea.show', $idea))
            ->assertDontSee('OP');
    }

    /** @test */
    public function comments_pagination_works()
    {
        $idea = Idea::factory()->create();

        Comment::factory((new Comment)->getPerPage() + 1)->create([
            'idea_id' => $idea->id,
        ]);

        $firstComment = Comment::first();
        $lastComment = Comment::orderByDesc('id')->first();

        $this->get(route('idea.show', $idea))
            ->assertSee($firstComment->body)
            ->assertDontSee($lastComment->body);

        $this->get(route('idea.show', [$idea, 'page' => 2]))
            ->assertDontSee($firstComment->body)
            ->assertSee($lastComment->body);
    }

    /** @test */
    public function edited_indicator_shows_only_if_comment_body_was_edited()
    {
        $commentOne = Comment::factory()->create();

        $commentTwo = Comment::factory()->create();

        $commentOne->spam_reports++;
        $commentOne->save();

        $commentTwo->body = 'My edited comment';
        $commentTwo->save();

        $this->get(route('idea.show', $commentOne->idea))
            ->assertDontSee('(edited)');

        $this->get(route('idea.show', $commentTwo->idea))
            ->assertDontSee('(edited)');
    }
}
