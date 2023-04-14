<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Models\Question;
use App\Services\AnswerService;
use App\Services\QuestionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function __construct(
        protected QuestionService $questionService,
        protected AnswerService $answerService
    ){}

    public function index(): View
    {
      $questions = Cache::remember(
          'questions',
          cache_duration(),
          fn () => $this->questionService->getAllQuestion()
      );

      return view('questions.index', [
            'questions' => $questions
      ]);
    }

    public function create(): View
    {
        return view('questions.create');
    }

    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        Cache::forget('questions');

        $store = $request->safe()->merge([
          'user_id' => auth()->id(),
          'slug' => Str::slug($request->title)
        ]);

        $this->questionService->createQuestion($store->all());

        return redirect('/questions')->with('message', 'Your question has been submitted');
    }

    public function show(Question $question, $slug): View
    {
        abort_if(
            $question->slug !== $slug,
            404,
        );

        $question_show = Cache::remember(
            'question-' . $question->id,
            cache_duration(),
            fn () => $this->questionService->getQuestion($question->id)
        );

        $answer = $this->answerService->getQuestionsAnswer($question->id);

        return view('questions.show', [
            'question' => $question_show,
            'answers' => $answer,
        ]);
    }

    public function edit(Question $question, $slug): View
    {
        abort_if(
            Gate::denies('users-allowed', $question->user_id),
            403
        );

        abort_if(
            $question->slug !== $slug,
            404,
        );

        $edit = $this->questionService->editQuestion($question, $question->tags);

        return view('questions.edit', [
            'question' => $edit['question'],
            'tags' => $edit['tags'],
        ]);
    }

    public function update(UpdateQuestionRequest $request, Question $question): RedirectResponse
    {
        Gate::authorize('users-allowed', $question->user_id);

        Cache::forget('quesetions');
        Cache::forget('question-' . $question->id);

        $update = $request->safe()->merge([
            'slug' => Str::slug($request->title)
        ]);

        $this->questionService->updateQuestion($update->all(), $question->id);

        return to_route('question.show', ['question' => $question, 'slug' => $update->slug])
            ->with('message', 'Your question has been updated!');
    }

    public function destroy(Question $question): RedirectResponse
    {
        Gate::authorize('users-allowed', $question->user_id);

        $question->tags()->detach();
        $question->delete();

        Cache::forget('questions');
        Cache::forget('question-' . $question->id);

        return to_route('question.index')
            ->with('message', 'Question deleted successfully!');
    }
}
