<?php

namespace App\Actions;

use App\Models\Tag;

class CreateQuestionTag
{
    public function handle($tags)
    {
        $tagIds = [];
        foreach ($tags as $tag) {
            Tag::firstOrCreate([
                'tags' => $tag
            ]);

            $tagName = Tag::where('tags', $tag)->first();
            array_push($tagIds, $tagName->id);
        }

        return $tagIds;
    }
}
