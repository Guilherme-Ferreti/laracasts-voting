<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdeaPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Idea $idea)
    {
        return $user->id === (int) $idea->user_id
            && now()->subHour() <= $idea->created_at;
    }

    public function delete(User $user, Idea $idea)
    {
        return $user->id === (int) $idea->user_id || $user->isAdmin();
    }

    public function markAsNotSpam(User $user)
    {
        return $user->isAdmin();
    }
}
