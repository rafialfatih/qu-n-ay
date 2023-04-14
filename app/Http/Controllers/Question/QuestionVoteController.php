<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\QuestionVote;
use App\Services\QuestionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class QuestionVoteController extends Controller
{
    public function __construct(
      protected QuestionService $questionService
    ){}

    public function __invoke(Request $request): RedirectResponse
    {
        Gate::authorize('users-vote');

        Cache::forget('question-'.$request->question_id);

        $this->questionService->questionVote(
          $request->question_id,
          $request->vote
        );

        return back();
    }
}
