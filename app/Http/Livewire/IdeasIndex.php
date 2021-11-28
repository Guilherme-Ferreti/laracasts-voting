<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\Vote;
use Livewire\Component;
use Livewire\WithPagination;

class IdeasIndex extends Component
{
    use WithPagination;

    public $status = 'All';
    public $category;

    protected $queryString = [
        'status',
        'category'
    ];

    protected $listeners = [
        'queryStringUpdateStatus',
    ];

    public function mount()
    {
        $this->status = request('status', 'All');
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function queryStringUpdateStatus(string $newStatus)
    {
        $this->resetPage();

        $this->status = $newStatus;
    }

    public function render()
    {
        $statuses = Status::all('id', 'name')->pluck('id', 'name');
        $categories = Category::all('id', 'name');

        return view('livewire.ideas-index', [
            'ideas' => Idea::with('user', 'category', 'status')
                ->when($this->status && $this->status !== 'All', fn ($query) => 
                    $query->where('status_id', $statuses->get($this->status))
                )
                ->when($this->category && $this->category !== 'All Categories', fn ($query) => 
                    $query->where('category_id', $categories->firstWhere('name', $this->category)?->id)
                )
                ->addSelect(['voted_by_user' => Vote::select('id')
                    ->where('user_id', auth()->id())
                    ->whereColumn('idea_id', 'ideas.id')
                ])
                ->withCount('votes')
                ->latest()
                ->simplePaginate(Idea::PAGINATION_COUNT),
            'categories' => $categories,
        ]);
    }
}
