<?php

namespace App\Http\Controllers\Answer;

use App\Http\Controllers\Controller;
use App\Models\AnswerVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnswerVoteController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('users-vote');

        $vote = AnswerVote::updateOrCreate(
            ['user_id' => auth()->id(), 'answer_id' => $request->answer_id],
            ['vote' => $request->vote]
        );

        if ($vote->wasRecentlyCreated === false) {
            if (!$vote->wasChanged('vote')) {
                $vote->delete();
            }
        }

        return back();
    }
}
