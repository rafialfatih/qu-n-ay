<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionVote;
use App\Models\Tag;
use Illuminate\Http\Response;

class QuestionService
{
    /**
     * Get all question
     */
    public function getAllQuestion()
    {
      return Question::with(['user', 'tags'])
          ->withCount('answers')
          ->votes()
          ->orderByDesc('created_at')
          ->paginate(15);
    }

    /**
     * Get single question
     */
    public function getQuestion(String $questionId)
    {
        return Question::with(['user', 'tags'])
            ->votes()
            ->where('id', $questionId)
            ->firstOrFail();
    }

    /**
     * Store question to database
     */
    public function createQuestion(array $question)
    {
        $tags = remove_tags_whitespace($question['tags']);
        $tagIds = $this->createQuestionTag($tags);

        return Question::create($question)
            ->tags()
            ->attach($tagIds);
    }

    /**
     * Update question
     */
    public function updateQuestion(array $question, String $id): void
    {
        $tags = remove_tags_whitespace($question['tags']);
        $tagIds = $this->createQuestionTag($tags);

        $query = Question::find($id);

        $query->update($question);

        $tagUpdate = $query->tags();
        $tagUpdate->detach();
        $tagUpdate->attach($tagIds);
    }

    /**
     * Create and filter tags to array
     */
    public function createQuestionTag(array $tags): array
    {
        $tagIds = [];
        foreach ($tags as $tag) {
            Tag::firstOrCreate([
                'tags' => $tag,
            ]);

            $tagName = Tag::where('tags', $tag)->first();
            array_push($tagIds, $tagName->id);
        }

        $tagIds = array_values(array_unique($tagIds));

        return $tagIds;
    }

    /**
     * Show tags to edit form
     */
    public function editQuestionTag($tags): String
    {
        $tagsArr = [];
        foreach ($tags as $tag) {
            array_push($tagsArr, $tag->tags);
        }
        $tagsValues = implode(', ', $tagsArr);

        return $tagsValues;
    }

    /**
     * Show edit form with tags
     */
    public function editQuestion($question, $tags): array
    {
        return [
          'question' => Question::where('id', $question->id)->firstOrFail(),
          'tags' => $this->editQuestionTag($tags)
        ];
    }

    /**
     * Upvote and downvote service
     */
    public function questionVote(String $questionId, String $votes): void
    {
        $vote = QuestionVote::updateOrCreate(
            ['user_id' => auth()->id(), 'question_id' => $questionId],
            ['vote' => $votes]
        );

        if ($vote->wasRecentlyCreated === false) {
            if (! $vote->wasChanged('vote')) {
                $vote->delete();
            }
        }
    }

    /**
     * Search question
     */
    public function questionSearch()
    {
        return Question::with(['user'])
            ->join('question_tag', 'questions.id', '=', 'question_tag.question_id')
            ->join('tags', 'tags.id', '=', 'question_tag.tag_id')
            ->select('questions.*')
            ->filter(request(['tag', 'q']))
            ->groupBy('questions.title')
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    /**
     * Get user's top questions
     */
    public function getUserTopQuestions($user, $total)
    {
        return $user->questions()
            ->votes()
            ->orderByDesc('upvotes_count')
            ->limit($total)
            ->get();
    }
}
