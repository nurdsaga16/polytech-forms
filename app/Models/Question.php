<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'title',
        'description',
        'question_type',
        'order',
        'survey_id',
        'section_id',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function textAnswers(): HasMany
    {
        return $this->hasMany(TextAnswer::class);
    }

    public function choiceAnswers(): HasMany
    {
        return $this->hasMany(ChoiceAnswer::class);
    }

    public function scaleAnswers(): HasMany
    {
        return $this->hasMany(ScaleAnswer::class);
    }

    public function answerOptions(): HasMany
    {
        return $this->hasMany(AnswerOption::class);
    }
}
