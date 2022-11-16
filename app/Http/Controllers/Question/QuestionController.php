<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Services\QuestionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('questions.index', [
            'questions' => Question::with(['user', 'tags'])->withCount(
                ['votes as upvotes_count' => fn (Builder $query) => $query->where('vote', 'up')]
            )
                ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuestionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionRequest $request, QuestionService $questionService)
    {
        $store = $request->safe()
            ->merge([
                'user_id' => auth()->id(),
                'slug' => Str::slug($request->title)
            ]);

        $questionService->createQuestion($store->all());

        return redirect('/questions')->with('message', 'Your question has been submitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question, $slug)
    {
        abort_if(
            $slug !== $question->slug,
            404,
        );

        $question = Question::withCount(
            ['votes as upvotes_count' => fn (Builder $query) => $query->where('vote', 'up')]
        )
            ->where('id', $question->id)
            ->firstOrFail();

        return view('questions.show', [
            'question' => $question,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question, QuestionService $questionService, $slug)
    {
        abort_if(
            $question->user_id !== auth()->id(),
            403,
        );

        abort_if(
            $question->slug !== $slug,
            404,
        );

        $question = Question::where('id', $question->id)->firstOrFail();
        $tags = $questionService->editQuestionTag($question->tags);

        return view('questions.edit', [
            'question' => $question,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuestionRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request, Question $question, QuestionService $questionService)
    {
        $update = $request->safe()->merge([
            'slug' => Str::slug($request->title)
        ]);

        $questionService->updateQuestion($update->all(), $question->id);

        return redirect()
            ->route('question.show', ['question' => $question, 'slug' => $update->slug])
            ->with('message', 'Your question has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        abort_if(
            $question->user_id !== auth()->id(),
            403,
        );

        $question->tags->detach();
        $question->delete();
    }
}
