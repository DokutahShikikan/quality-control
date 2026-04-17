<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dataset extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'headers' => 'array',
            'metrics' => 'array',
            'deepseek_enabled' => 'boolean',
            'last_checked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(DatasetRow::class);
    }

    public function activeRows(): HasMany
    {
        return $this->hasMany(DatasetRow::class)->where('is_active', true);
    }

    public function checkRuns(): HasMany
    {
        return $this->hasMany(CheckRun::class)->latest();
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class)->latest();
    }

    public function duplicateCandidates(): HasMany
    {
        return $this->hasMany(DuplicateCandidate::class)->latest();
    }
}
