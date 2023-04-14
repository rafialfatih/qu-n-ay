<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\QuestionService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected QuestionService $questionService
    ){}

    public function show(User $user): View
    {
        $user_profile = Cache::remember(
            'user-'.$user->username,
            now()->addMinutes(10),
            fn () => $this->userService->getUserData($user)
        );

        $top_questions = Cache::remember(
            'top-questions'.$user->id,
            now()->addMinutes(10),
            fn () => $this->questionService->getUserTopQuestions($user, 5)
        );

        return view('users.show', [
            'user' => $user_profile,
            'top_questions' => $top_questions,
        ]);
    }

    public function edit(User $user): View
    {
        if (Gate::denies('users-edit', $user->id)) {
            abort(403);
        }

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('users-edit', $user->id);

        $update = $request->validated();

        $user->update($update);

        return to_route('user.edit', [$user->username])
            ->with('message', 'Profile updated successfully!');
    }
}
