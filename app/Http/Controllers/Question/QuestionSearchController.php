<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Services\QuestionService;
use Illuminate\View\View;

class QuestionSearchController extends Controller
{
    public function __construct(
        protected QuestionService $questionService
    ){}

    public function __invoke(): View
    {
        return view('search.index', [
            'search' => $this->questionService->questionSearch(),
        ]);
    }
}
