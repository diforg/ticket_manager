<?php

namespace App\Policies;

use App\Models\User;

class TicketPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function update(User $user): bool
    {
        return $user->role === 'attendant';
    }
}