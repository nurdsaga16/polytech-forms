<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class ScaleAnswer extends Model
{
    protected $table = 'scale_answers';

    protected $fillable = [
        'response_id',
        'question_id',
        'answer',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function survey(): HasOneThrough
    {
        return $this->hasOneThrough(Survey::class, Response::class, 'id', 'id', 'response_id', 'survey_id');
    }
}
