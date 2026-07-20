<?php

namespace App\Actions;

use App\Models\Ticket;
use App\Models\User;

class CreateTicketAction
{
    public function handle(User $user, array $data): Ticket
    {
        return Ticket::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'user_id' => $user->id,
            'status' => Ticket::STATUS_OPEN,
        ]);
    }
}