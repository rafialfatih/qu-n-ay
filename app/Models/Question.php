<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Traits\UseUuid;
use Carbon\Carbon;

class Question extends Model
{
  use HasFactory;
  use UseUuid;

  protected $dates = [
    'created_at',
    'updated_at'
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
  }

  public function scopeAnswers($query)
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
