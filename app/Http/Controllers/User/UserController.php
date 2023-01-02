<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user_profile = Cache::remember('user-'.$user->username, now()->addMinutes(10), function () use ($user) {
            return User::with(['questions', 'answers'])
                ->withCount([
                    'questions as questions_count' => fn (Builder $query) => $query->where('user_id', $user->id),
                    'answers as answers_count' => fn (Builder $query) => $query->where('user_id', $user->id),
                ])
                ->where('id', $user->id)
                ->firstOrFail(['username', 'name']);
        });

        $top_questions = Cache::remember('top-questions'.$user->id, now()->addMinutes(10), function () use ($user) {
            return $user->questions()
                ->votes()
                ->orderByDesc('upvotes_count')
                ->limit(5)
                ->get();
        });

        return view('users.show', [
            'user' => $user_profile,
            'top_questions' => $top_questions,
        ]);
    }

    public function edit(User $user)
    {
        if (Gate::denies('users-edit', $user->id)) {
            abort(403);
        }

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('users-edit', $user->id);

        $update = $request->validated();

        $user->update($update);

        return redirect()
            ->route('user.edit', [$user->username])
            ->with('message', 'Profile updated successfully!');
    }
}
