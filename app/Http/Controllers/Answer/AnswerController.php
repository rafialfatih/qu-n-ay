<?php

namespace App\Http\Controllers\Answer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Answer\StoreAnswerRequest;
use App\Http\Requests\Answer\UpdateAnswerRequest;
use App\Models\Answer;
use App\Models\Question;
use App\Services\AnswerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnswerController extends Controller
{
    public function __construct(
        protected AnswerService $answerService
    ){}

    public function store(StoreAnswerRequest $request): RedirectResponse
    {
        $answer = $request->safe()->merge([
            'user_id' => auth()->id(),
            'question_id' => $request->question_id,
        ]);

        Answer::create($answer->all());

        return back()->with('message', 'Your answer has been submitted');
    }

    public function edit(Question $question): View
    {
        $answer = $this->answerService->getAnswerData($question);

        abort_if(
            $answer->user_id !== auth()->id(),
            403,
        );

        return view('answers.edit', [
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function update(UpdateAnswerRequest $request, Question $question): RedirectResponse
    {
        $update = $request->validated();

        $answer = $this->answerService->getAnswerData($question);

        abort_if(
            $answer->user_id !== auth()->id(),
            403,
        );

        $answer->update($update);

        return to_route('question.show', [$question->id, $question->slug])
            ->with('message', 'Your answer has been updated successfully!');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $answer = $this->answerService->getAnswerData($question);

        abort_if(
            $answer->user_id !== auth()->id(),
            403,
        );

        $answer->delete();

        return to_route('question.show', [$question->id, $question->slug])
            ->with('message', 'Your answer has been deleted successfully!');
    }
}
