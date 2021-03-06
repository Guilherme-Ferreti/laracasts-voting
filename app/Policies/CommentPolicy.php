<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment)
    {
        return $comment->isAuthoredBy($user);
    }

    public function delete(User $user, Comment $comment)
    {
        return $comment->isAuthoredBy($user) || $user->isAdmin();
    }

    public function markAsNotSpam(User $user)
    {
        return $user->isAdmin();
    }
}
