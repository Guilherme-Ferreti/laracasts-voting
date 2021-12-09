<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use Livewire\Component;
use App\Jobs\NotifyAllVoters;
use Illuminate\Http\Response;

class SetStatus extends Component
{
    public $idea;
    public $status;
    public $comment;
    public $notifyAllVoters;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->status = $this->idea->status_id;
    }

    public function setStatus()
    {
        abort_if(auth()->guest() || ! auth()->user()->isAdmin(), Response::HTTP_FORBIDDEN);

        if ($this->idea->status_id == $this->status) {
            $this->emit('statusWasUpdatedError', 'Status is the same!');
            return;
        }

        $this->idea->status_id = $this->status;
        $this->idea->save();

        if ($this->notifyAllVoters) {
            NotifyAllVoters::dispatch($this->idea);
        }

        $this->idea->comments()->create([
            'body' => $this->comment ?? 'No comment was added.',
            'user_id' => auth()->id(),
            'status_id' => $this->status,
            'is_status_update' => true,
        ]);

        $this->reset('comment');

        $this->emit('statusWasUpdated', 'Idea status updated successfully!');
    }

    public function render()
    {
        return view('livewire.set-status');
    }
}
