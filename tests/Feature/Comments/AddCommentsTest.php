<?php

namespace Tests\Feature\Comments;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\AddComment;
use App\Notifications\CommentAdded;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class AddCommentsTest extends TestCase
{
    // Obs: This test class does not follow already written test pattern because author is trying new ways.  

    use RefreshDatabase;

    /** @test */
    public function add_comment_livewire_component_renders()
    {
        $this->get(route('idea.show', Idea::factory()->create()))
            ->assertSeeLivewire('add-comment');
    }

    /** @test */
    public function add_comment_form_renders_when_user_is_logged_in()
    {
        $this->actingAs(User::factory()->create())
            ->get(route('idea.show', Idea::factory()->create()))   
            ->assertSee('Share your thoughts...');
    }

    /** @test */
    public function add_comment_form_does_not_render_when_user_is_logged_out()
    {
        $this->get(route('idea.show', Idea::factory()->create()))
            ->assertSee('Please login or create an account to post a comment.')
            ->assertDontSee('Share your thoughts...');
    }

    /** @test */
    public function add_comment_form_validation_works()
    {
        $invalidComments = ['', 'abc', str_repeat('a', 5001)];

        $component = Livewire::actingAs(User::factory()->create())
            ->test(AddComment::class, ['idea' => Idea::factory()->create()]);

        foreach ($invalidComments as $comment) {
            $component->set('comment', $comment)
                ->call('addComment')
                ->assertHasErrors(['comment']);
        }
    }

    /** @test */
    public function add_comment_form_works()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        Notification::fake();

        Notification::assertNothingSent();

        Livewire::actingAs($user)
            ->test(AddComment::class, ['idea' => $idea])
            ->set('comment', 'My first comment!')
            ->call('addComment')
            ->assertEmitted('commentWasAdded');

        Notification::assertSentTo($idea->user, CommentAdded::class);

        $this->assertDatabaseHas('comments', [
            'body' => 'My first comment!',
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);
    }
}
