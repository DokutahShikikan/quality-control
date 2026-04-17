<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuplicateCandidate extends Model
{
    protected $guarded = [];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }

    public function checkRun(): BelongsTo
    {
        return $this->belongsTo(CheckRun::class);
    }

    public function primaryRow(): BelongsTo
    {
        return $this->belongsTo(DatasetRow::class, 'primary_row_id');
    }

    public function duplicateRow(): BelongsTo
    {
        return $this->belongsTo(DatasetRow::class, 'duplicate_row_id');
    }
}
