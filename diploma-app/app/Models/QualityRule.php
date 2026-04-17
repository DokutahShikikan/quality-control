<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityRule extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'column_hints' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
