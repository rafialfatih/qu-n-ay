<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\QuestionVote;
use Illuminate\Http\Request;

class QuestionVoteController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function __invoke(Request $request)
  {
    $vote = QuestionVote::updateOrCreate(
      ['user_id' => auth()->id(), 'question_id' => $request->question_id],
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
