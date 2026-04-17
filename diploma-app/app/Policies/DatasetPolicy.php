<?php

namespace App\Policies;

use App\Models\Dataset;
use App\Models\User;

class DatasetPolicy
{
    public function update(User $user, Dataset $dataset): bool
    {
        return $user->is($dataset->user);
    }
}
