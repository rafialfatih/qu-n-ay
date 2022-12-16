<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    public function show(User $user)
    {
        $user = User::with(['questions', 'answers'])
          ->withCount([
              'questions as questions_count' => fn (Builder $query) => $query->where('user_id', $user->id),
          ])
          ->withCount([
              'answers as answers_count' => fn (Builder $query) => $query->where('user_id', $user->id),
          ])
          ->where('id', $user->id)
          ->firstOrFail();

        $top_questions = $user->questions()
          ->with('votes')
          ->withCount([
              'votes as top_votes' => fn (Builder $query) => $query->where('vote', 'up')->whereColumn('question_id', 'questions.id'),
          ])
          ->orderByDesc('top_votes')
          ->get();

        return view('users.show', [
            'user' => $user,
            'top_questions' => $top_questions,
        ]);
    }
}
