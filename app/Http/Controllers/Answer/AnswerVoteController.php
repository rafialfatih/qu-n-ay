<?php

namespace App\Http\Controllers\Answer;

use App\Http\Controllers\Controller;
use App\Models\AnswerVote;
use App\Services\AnswerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class AnswerVoteController extends Controller
{
    public function __construct(
        protected AnswerService $answerService
    ){}

    public function __invoke(Request $request): RedirectResponse
    {
        Gate::authorize('users-vote');

        Cache::forget('question-'.$request->question_id);

        $this->answerService->answerVote(
            ['user_id' => auth()->id(), 'answer_id' => $request->answer_id],
            ['vote' => $request->vote]
        );

        return back();
    }
}
