<?php

namespace App\Http\Controllers\Question;

use App\Actions\CreateQuestionTag;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
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
            'questions' => Question::with(['user', 'tags'])
                ->votes()
                ->orderBy('created_at', 'desc')
                ->get(),
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
    public function store(StoreQuestionRequest $request, CreateQuestionTag $createQuestionTag)
    {
        $question = $request->safe()->merge([
            'user_id' => auth()->id(),
            'slug' => Str::slug($request->title)
        ]);

        $tags = remove_tags_whitespace($request->tags);
        $tagIds = $createQuestionTag->handle($tags);

        Question::create($question->all())
            ->tags()
            ->attach($tagIds);

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
    public function edit(Question $question)
    {
        abort_if(
            $question->user_id !== auth()->id(),
            403,
        );

        return view('questions.edit', [
            'question' => $question
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuestionRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $field = $request->validated();

        $question->update($field);

        return back()->with('message', 'Question updated!');
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

        $question->delete();
    }
}
