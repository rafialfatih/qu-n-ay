<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Answer extends Model
{
  use HasFactory, HasUuids;

  protected $fillable = [
    'user_id',
    'question_id',
    'answer'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function question(): BelongsTo
  {
    return $this->belongsTo(Question::class);
  }

  public function votes(): HasMany
  {
    return $this->hasMany(AnswerVote::class);
  }

  public function createdAt(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => Carbon::parse($value)->shortRelativeDiffForHumans()
    );
  }
}
