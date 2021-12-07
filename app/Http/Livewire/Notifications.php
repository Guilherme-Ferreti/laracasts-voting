<?php

namespace App\Http\Livewire;

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

    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => $this->notifications,
        ]);
    }
}
