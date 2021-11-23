<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Idea;
use Illuminate\Http\Response;
use Livewire\Component;

class CreateIdea extends Component
{
    public $title;
    public $category_id = 1;
    public $description;

    protected $rules = [
        'title' => 'required|string|min:4|max:255',
        'category_id' => 'required|integer|exists:categories',
        'description' => 'required|string|min:4|max:5000',
    ];

    public function createIdea()
    {
        abort_if(auth()->guest(), Response::HTTP_FORBIDDEN);

        $this->validate();

        Idea::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'status_id' => 1,
            'title' => $this->title,
            'description' => $this->description,
        ]);

        session()->flash('success_message', 'Idea was created sucessfully!');

        $this->reset();

        return redirect()->route('idea.index');
    }
    
    public function render()
    {
        return view('livewire.create-idea', [
            'categories' => Category::all(),
        ]);
    }
}
