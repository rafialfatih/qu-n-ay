<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserService
{
    public function getUserData($user)
    {
        return User::with(['questions', 'answers'])
            ->withCount([
                'questions as questions_count' => fn (Builder $query) => $query->where('user_id', $user->id),
                'answers as answers_count' => fn (Builder $query) => $query->where('user_id', $user->id),
            ])
            ->where('id', $user->id)
            ->firstOrFail(['username', 'name']);
    }
}
