<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\AnswerVote;
use App\Models\Question;

class AnswerService
{
    public function getQuestionsAnswer(String $questionId)
    {
        return Answer::with('user')
            ->votes()
            ->where('question_id', $questionId)
            ->get();
    }

    public function answerVote(Array $ids, Array $votes): Void
    {
        $vote = AnswerVote::updateOrCreate($ids, $votes);

        if ($vote->wasRecentlyCreated === false) {
            if (! $vote->wasChanged('vote')) {
                $vote->delete();
            }
        }
    }

    public function getAnswerData(Question $question)
    {
        return $question
            ->answers()
            ->with(['questions', 'user'])
            ->first();
    }
}
