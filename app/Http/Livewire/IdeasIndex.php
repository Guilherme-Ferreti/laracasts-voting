<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use App\Models\Vote;
use App\Models\Status;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use App\Http\Livewire\Traits\WithAuthRedirects;

class IdeasIndex extends Component
{
    use WithPagination, WithAuthRedirects;

    public $status = 'All';
    public $category;
    public $filter;
    public $search;

    protected $queryString = [
        'status',
        'category',
        'filter',
        'search',
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

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter($newFilter)
    {
        if ($this->filter === 'My Ideas' && auth()->guest()) {
            return $this->redirectToLogin();
        }
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
                ->when($this->filter && $this->filter === 'Top Voted', fn ($query) => 
                    $query->orderByDesc('votes_count')
                )
                ->when($this->filter && $this->filter === 'My Ideas', fn ($query) => 
                    $query->where('user_id', auth()->id())
                )
                ->when($this->filter && $this->filter === 'Spam Ideas', fn ($query) => 
                    $query->where('spam_reports', '>', 0)->orderByDesc('spam_reports')
                )
                ->when($this->filter && $this->filter === 'Spam Comments', fn ($query) => 
                    $query->whereHas('comments', fn ($query) => 
                        $query->where('spam_reports', '>', 0) 
                    )
                )
                ->when(strlen($this->search) > 3, fn ($query) => 
                    $query->where('title', 'like', '%' . $this->search . '%')
                )
                ->addSelect(['voted_by_user' => Vote::select('id')
                    ->where('user_id', auth()->id())
                    ->whereColumn('idea_id', 'ideas.id')
                ])
                ->withCount('votes', 'comments')
                ->orderByDesc('id')
                ->simplePaginate()
                ->withQueryString(),
            'categories' => $categories,
        ]);
    }
}
