<?php

namespace App\Policies;

use App\Models\TimetableEntry;
use App\Models\User;

class TimetableEntryPolicy
{
    public function delete(User $user, TimetableEntry $entry): bool
    {
        return $user->id === $entry->user_id;
    }
}
