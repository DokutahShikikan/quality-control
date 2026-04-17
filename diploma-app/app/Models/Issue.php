<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function checkRun(): BelongsTo
    {
        return $this->belongsTo(CheckRun::class);
    }

    public function datasetRow(): BelongsTo
    {
        return $this->belongsTo(DatasetRow::class);
    }
}
