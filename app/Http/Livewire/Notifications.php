<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class Notifications extends Component
{
    const NOTIFICATION_THRESHOLD = 20; 

    public $notifications;
    public $notificationCount;
    public $isLoading;

    protected $listeners = [
        'getNotifications',
    ];

    public function mount()
    {
        $this->notifications = collect([]);
        $this->isLoading = true;
        $this->getNotificationCount();
    }

    public function getNotificationCount()
    {
        $this->notificationCount = auth()->user()->unreadNotifications()->count();

        if ($this->notificationCount > Self::NOTIFICATION_THRESHOLD) {
            $this->notificationCount = Self::NOTIFICATION_THRESHOLD . '+';
        }
    }

    public function getNotifications()
    {
        $this->notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(Self::NOTIFICATION_THRESHOLD)
            ->get();

        $this->isLoading = false;
    }

    public function markAsRead(string $notification_id)
    {
        abort_if(auth()->guest(), Response::HTTP_FORBIDDEN);
        
        $notification = DatabaseNotification::findOrFail($notification_id);
        $notification->markAsRead();

        $this->scrollToComment($notification);
    }

    public function markAllAsRead()
    {
        abort_if(auth()->guest(), Response::HTTP_FORBIDDEN);

        auth()->user()->unreadNotifications->markAsRead();

        $this->getNotificationCount();
        $this->getNotifications();
    }

    public function scrollToComment($notification)
    {
        $idea = Idea::find($notification->data['idea_id']);

        if (! $idea) {
            session()->flash('error_message', 'This idea no longer exists!');

            return redirect()->route('idea.index');
        }

        $comment = Comment::find($notification->data['comment_id']);

        if (! $comment) {
            session()->flash('error_message', 'This comment no longer exists!');

            return redirect()->route('idea.index');
        }

        $comments = $idea->comments()->pluck('id');
        $indexOfComment = $comments->search($comment->id);

        $page = (int) ($indexOfComment / $comment->getPerPage()) + 1;

        session()->flash('scrollToComment', $comment->id);

        return redirect()->route('idea.show', [
            'idea' => $notification->data['idea_slug'],
            'page' => $page,
        ]);
    }

    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => $this->notifications,
        ]);
    }
}
