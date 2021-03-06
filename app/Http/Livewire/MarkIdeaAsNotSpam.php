<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use Illuminate\Http\Response;
use Livewire\Component;

class MarkIdeaAsNotSpam extends Component
{    
    public $idea;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function markAsNotSpam()
    {
        abort_if(auth()->guest() || auth()->user()->cannot('markAsNotSpam', Idea::class), Response::HTTP_FORBIDDEN);

        $this->idea->spam_reports = 0;
        $this->idea->save();

        $this->emit('ideaWasMarkedAsNotSpam', __('Spam counter was reset!'));
    }
    
    public function render()
    {
        return view('livewire.mark-idea-as-not-spam');
    }
}
