<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;

class IdeaComment extends Component
{
    public $comment;
    public $ideaAuthorId;

    protected $listeners = [
        'commentWasUpdated',
        'commentWasMarkedAsSpam',
        'commentWasMarkedAsNotSpam',
    ];

    public function commentWasUpdated()
    {
        $this->comment->refresh();
    }

    public function commentWasMarkedAsSpam()
    {
        $this->comment->refresh();
    }

    public function commentWasMarkedAsNotSpam()
    {
        $this->comment->refresh();
    }

    public function mount(Comment $comment, $ideaAuthorId)
    {
        $this->comment = $comment;
        $this->ideaAuthorId = $ideaAuthorId;
    }

    public function render()
    {
        return view('livewire.idea-comment');
    }
}
