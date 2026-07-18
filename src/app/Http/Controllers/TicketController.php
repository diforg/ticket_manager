<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function clientIndex(Request $request): Response
    {
        $tickets = Ticket::query()
            ->whereBelongsTo($request->user())
            ->latest()
            ->get(['id', 'title', 'status', 'created_at', 'updated_at']);

        return Inertia::render('Dashboard/Client', [
            'tickets' => $tickets->map(fn (Ticket $ticket): array => [
                'id' => $ticket->id,
                'title' => $ticket->title,
                'status' => $ticket->status,
                'created_at' => $ticket->created_at?->toISOString(),
                'updated_at' => $ticket->updated_at?->toISOString(),
            ]),
        ]);
    }

    public function attendantIndex(Request $request): Response
    {
        $requestedStatus = $request->string('status')->toString();
        $validStatuses = Ticket::validStatuses();
        $statusFilter = in_array($requestedStatus, $validStatuses, true) ? $requestedStatus : null;

        $tickets = Ticket::query()
            ->with('user:id,name')
            ->status($statusFilter)
            ->latest()
            ->get(['id', 'user_id', 'title', 'status', 'created_at', 'updated_at']);

        return Inertia::render('Dashboard/Attendant', [
            'filters' => [
                'status' => $statusFilter,
            ],
            'tickets' => $tickets->map(fn (Ticket $ticket): array => [
                'id' => $ticket->id,
                'title' => $ticket->title,
                'status' => $ticket->status,
                'requester' => $ticket->user?->name,
                'created_at' => $ticket->created_at?->toISOString(),
                'updated_at' => $ticket->updated_at?->toISOString(),
            ]),
        ]);
    }
}
