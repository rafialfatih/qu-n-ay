<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'answer_id',
        'vote',
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
