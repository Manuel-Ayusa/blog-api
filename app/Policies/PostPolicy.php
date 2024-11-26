<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function author(User $user, Post $post): bool
    {
        if ($post->user->id == $user->id) {
            return true;    
        } else {
            return false;
        }
    }
}
