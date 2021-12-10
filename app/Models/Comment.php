<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $perPage = 10;

    protected $casts = [
        'body_updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function isAuthoredBy(int|User $user)
    {
        $user_id = is_numeric($user) ? $user : $user->id;

        return $this->user_id == $user_id;
    }

    public function bodyWasEdited(): bool
    {
        return $this->body_updated_at && $this->body_updated_at->gt($this->created_at);
    }

    public static function booted()
    {
        static::updating(function ($comment) {
            if ($comment->body !== $comment->getOriginal('body')) {
                $comment->body_updated_at = now();
            }
        });
    }
}
