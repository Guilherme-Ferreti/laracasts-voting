<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Http\Response;

class EditIdea extends Component
{
    public $idea;
    public $title;
    public $category_id = 1;
    public $description;

    protected $rules = [
        'title' => 'required|string|min:4|max:255',
        'category_id' => 'required|integer|exists:categories,id',
        'description' => 'required|string|min:4|max:5000',
    ];

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->title = $idea->title;
        $this->category_id = $idea->category_id;
        $this->description = $idea->description;
    }

    public function updateIdea()
    {
        if (auth()->guest() || auth()->user()->cannot('update', $this->idea)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();

        $this->idea->update([
            'title' => $this->title,
            'category_id' => $this->category_id,
            'description' => $this->description,
        ]);

        $this->emit('ideaWasUpdated', 'Idea was updated successfully!');
    }
    
    public function render()
    {
        return view('livewire.edit-idea', [
            'categories' => Category::all(),
        ]);
    }
}
