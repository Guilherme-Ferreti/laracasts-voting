<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Http\Response;
use App\Http\Livewire\IdeaShow;
use App\Http\Livewire\IdeaIndex;
use App\Http\Livewire\MarkIdeaAsSpam;
use App\Http\Livewire\MarkIdeaAsNotSpam;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpamManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shows_mark_idea_as_spam_idea_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-idea-as-spam');
    }

    /** @test */
    public function does_not_show_mark_idea_as_spam_idea_livewire_component_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        $this->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('mark-idea-as-spam');
    }

    /** @test */
    public function marking_an_idea_as_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(MarkIdeaAsSpam::class, ['idea' => $idea])
            ->call('markAsSpam')
            ->assertEmitted('ideaWasMarkedAsSpam');

        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'spam_reports' => 1,
        ]);
    }

    /** @test */
    public function marking_an_idea_as_spam_does_not_work_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        Livewire::test(MarkIdeaAsSpam::class, ['idea' => $idea])
            ->call('markAsSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'spam_reports' => 0,
        ]);
    }

    /** @test */
    public function marking_an_idea_as_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(IdeaShow::class, ['idea' => $idea])
            ->assertSee('Mark as Spam');
    }

    /** @test */
    public function  marking_an_idea_as_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $idea = Idea::factory()->create();

        Livewire::test(IdeaShow::class, ['idea' => $idea])
            ->assertDontSee('Mark as Spam');
    }

    /** @test */
    public function shows_mark_idea_as_not_spam_idea_livewire_component_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertSeeLivewire('mark-idea-as-not-spam');
    }

    /** @test */
    public function does_not_show_mark_idea_as_not_spam_idea_livewire_component_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $this->actingAs($user)
            ->get(route('idea.show', $idea))
            ->assertDontSeeLivewire('mark-idea-as-not-spam');
    }

    /** @test */
    public function marking_an_idea_as_not_spam_works_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();

        $idea = Idea::factory()->create([
            'spam_reports' => 10,
        ]);

        Livewire::actingAs($user)
            ->test(MarkIdeaAsNotSpam::class, ['idea' => $idea])
            ->call('markAsNotSpam')
            ->assertEmitted('ideaWasMarkedAsNotSpam');

        $this->assertEquals(0, Idea::first()->spam_reports);
    }

    /** @test */
    public function marking_an_idea_as_not_spam_does_not_work_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            'spam_reports' => 10,
        ]);

        Livewire::actingAs($user)
            ->test(MarkIdeaAsNotSpam::class, ['idea' => $idea])
            ->call('markAsNotSpam')
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertEquals(10, Idea::first()->spam_reports);  
    }

    /** @test */
    public function marking_an_idea_as_not_spam_shows_on_menu_when_user_has_authorization()
    {
        $user = User::factory()->admin()->create();
        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(IdeaShow::class, ['idea' => $idea])
            ->assertSee('Not Spam');
    }

    /** @test */
    public function  marking_an_idea_as_not_spam_does_not_show_on_menu_when_user_does_not_have_authorization()
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        Livewire::actingAs($user)
            ->test(IdeaShow::class, ['idea' => $idea])
            ->assertDontSee('Not Spam');
    }

    /** @test */
    public function spam_reports_count_shows_on_idea_index_page_if_logged_in_as_admin()
    {
        $user = User::factory()->admin()->create();

        $idea = Idea::factory()->create([
            'spam_reports' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaIndex::class, ['idea' => $idea])
            ->assertSee('Spam Reports: 3');
    }

    /** @test */
    public function  smap_reports_count_shows_on_idea_show_page_if_logged_in_as_admin()
    {
        $user = User::factory()->admin()->create();

        $idea = Idea::factory()->create([
            'spam_reports' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(IdeaShow::class, ['idea' => $idea])
            ->assertSee('Spam Reports: 3');
    }
}
