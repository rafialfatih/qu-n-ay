<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\Traits\UseUuid;
use Illuminate\Database\Eloquent\Builder;

class Question extends Model
{
    use HasFactory;
    use UseUuid;

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
    }
}
