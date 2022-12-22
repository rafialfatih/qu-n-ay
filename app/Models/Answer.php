<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Answer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'question_id',
        'answer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questions(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function answerVotes(): HasMany
    {
        return $this->hasMany(AnswerVote::class);
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->shortRelativeDiffForHumans()
        );
    }

    public function scopeVotes($query)
    {
        $query->withCount(['answerVotes as upvotes_count' => function (Builder $query) {
            $query->select(
                DB::raw(
                    '(count(case when answer_votes.vote = "up" then 1 else null end) -
                    count(case when answer_votes.vote = "down" then 1 else null end))'
                )
            );
        }]);
    }
}
