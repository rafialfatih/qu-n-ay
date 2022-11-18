<?php

namespace App\Http\Controllers\Answer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Answer\StoreAnswerRequest;
use App\Http\Requests\Answer\UpdateAnswerRequest;
use App\Models\Answer;

class AnswerController extends Controller
{
  public function store(StoreAnswerRequest $request)
  {
    $answer = $request->safe()->merge([
      'user_id' => auth()->id(),
      'question_id' => $request->question_id
    ]);

    Answer::create($answer->all());

    return back()->with('message', 'Your answer has been submitted');
  }

  public function update(UpdateAnswerRequest $request, Answer $answer)
  {
    $update = $request->validated();
  }

  public function destroy(Answer $answer)
  {
    abort_if(
      $answer->user_id !== auth()->id(),
      403,
    );
  }
}
