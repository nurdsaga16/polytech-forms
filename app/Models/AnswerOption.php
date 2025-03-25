<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class AnswerOption extends Model
{
    protected $table = 'answer_options';

    protected $fillable = [
        'title',
        'order',
        'question_id',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function survey(): HasOneThrough
    {
        return $this->hasOneThrough(Survey::class, Question::class, 'id', 'id', 'question_id', 'survey_id');
    }

    public function choiceAnswers(): HasMany
    {
        return $this->hasMany(ChoiceAnswer::class);
    }
}
