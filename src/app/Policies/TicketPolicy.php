<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'attendant') {
            return true;
        }

        return $user->role === 'client' && $ticket->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    public function update(User $user): bool
    {
        return $user->role === 'attendant';
    }

    public function message(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }
}