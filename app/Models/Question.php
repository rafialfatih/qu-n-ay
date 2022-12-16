<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory, HasUuids;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'question',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(QuestionVote::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeVotes($query)
    {
        $query->withCount(
            ['votes as upvotes_count' => fn (Builder $query) => $query->where('vote', 'up')]
        );
        // $query->select(DB::raw(
        //   '(count(case when question_votes.vote = "up" then 1 else null end) -
    //     count(case when question_votes.vote = "down" then 1 else null end))
    //     as upvotes_count
        //   '
        // ))
        //   ->from(['question_votes', 'questions'])
        //   ->whereIn('question_votes.question_id', DB::table('questions')->pluck('id')->toArray())
        //   ->groupBy('question_votes.question_id');
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['tag'] ?? false) {
            $query->where('tags.tags', 'LIKE', '%'.request('tag').'%');
        }

        if ($filters['q'] ?? false) {
            $query->where('questions.title', 'LIKE', '%'.request('q').'%')
              ->orWhere('tags.tags', 'LIKE', '%'.request('q').'%');
        }
    }

    public function scopeAnswersCount($query)
    {
        $query->withCount('answers');
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->shortRelativeDiffForHumans()
        );
    }
}
