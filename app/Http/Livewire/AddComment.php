<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use Illuminate\Http\Response;
use Livewire\Component;

class AddComment extends Component
{
    public $idea;
    public $comment;

    protected $rules = [
        'comment' => 'required|string|min:4|max:5000',
    ];

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function addComment()
    {
        abort_if(auth()->guest(), Response::HTTP_FORBIDDEN);

        $this->validate();

        $this->idea->comments()->create([
            'body' => $this->comment,
            'user_id' => auth()->id(),
        ]);

        $this->reset();

        $this->emit('commentWasAdded', 'Comment was posted!');
    }
    
    public function render()
    {
        return view('livewire.add-comment');
    }
}
