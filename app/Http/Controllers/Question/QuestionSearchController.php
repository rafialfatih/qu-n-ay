<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionSearchController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('search.index', [
            'search' => Question::with(['tags', 'user'])
                ->join('question_tag', 'questions.id', '=', 'question_tag.question_id')
                ->join('tags', 'tags.id', '=', 'question_tag.tag_id')
                ->select('questions.*')
                ->filter(request(['tag', 'q']))
                ->groupBy('questions.title')
                ->orderByDesc('created_at')
                ->paginate(15)
        ]);
    }
}
