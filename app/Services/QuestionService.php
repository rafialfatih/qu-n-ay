<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Tag;

class QuestionService
{
    /**
     * Store question to database
     */
    public function createQuestion($question)
    {
        $tags = remove_tags_whitespace($question['tags']);
        $tagIds = $this->createQuestionTag($tags);

        Question::create($question)
            ->tags()
            ->attach($tagIds);
    }

    /**
     * Update question
     */
    public function updateQuestion($question, $id)
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
    public function createQuestionTag($tags)
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
    public function editQuestionTag($tags)
    {
        $tagsArr = [];
        foreach ($tags as $tag) {
            array_push($tagsArr, $tag->tags);
        }
        $tagsValues = implode(', ', $tagsArr);

        return $tagsValues;
    }
}
