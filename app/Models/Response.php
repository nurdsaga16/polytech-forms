<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class Response extends Model
{
    protected $table = 'responses';

    protected $fillable = [
        'survey_id',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function group(): HasOneThrough
    {
        return $this->hasOneThrough(Group::class, Survey::class, 'id', 'id', 'survey_id', 'schedule_id');
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
}
