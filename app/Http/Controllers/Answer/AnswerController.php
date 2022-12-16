<?php

namespace App\Http\Controllers\Answer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Answer\StoreAnswerRequest;
use App\Http\Requests\Answer\UpdateAnswerRequest;
use App\Models\Answer;
use App\Models\Question;

class AnswerController extends Controller
{
    public function store(StoreAnswerRequest $request)
    {
        $answer = $request->safe()->merge([
            'user_id' => auth()->id(),
            'question_id' => $request->question_id,
        ]);

        Answer::create($answer->all());

        return back()->with('message', 'Your answer has been submitted');
    }

    public function edit(Question $question)
    {
        $answer = $question->answers()->with(['questions', 'user'])->first();

        abort_if(
            $answer->user_id !== auth()->id(),
            403,
        );

        return view('answers.edit', [
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function update(UpdateAnswerRequest $request, Question $question)
    {
        $update = $request->validated();

        $answer = $question->answers()->with(['question', 'user'])->first();

        abort_if(
            $answer->user_id !== auth()->id(),
            403,
        );

        $answer->update($update);

        return redirect()
          ->route('question.show', [$question->id, $question->slug])
          ->with('message', 'Your answer has been updated successfully!');
    }

    public function destroy(Question $question)
    {
        $answer = $question->answers()->with(['question', 'user'])->first();

        abort_if(
            $answer->user_id !== auth()->id(),
            403,
        );

        $answer->delete();

        return redirect()
          ->route('question.show', [$question->id, $question->slug])
          ->with('message', 'Your answer has been deleted successfully!');
    }
}
