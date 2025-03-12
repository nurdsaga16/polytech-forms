<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class Survey extends Model
{
    protected $table = 'surveys';

    protected $fillable = [
        'title',
        'description',
        'response_limit',
        'schedule_id',
        'active',
        'template',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Schedule::class, 'id', 'id', 'schedule_id', 'user_id');
    }

    public function practice(): HasOneThrough
    {
        return $this->hasOneThrough(Practice::class, Schedule::class, 'id', 'id', 'schedule_id', 'practice_id');
    }

    public function group(): HasOneThrough
    {
        return $this->hasOneThrough(Group::class, Schedule::class, 'id', 'id', 'schedule_id', 'group_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function response(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
