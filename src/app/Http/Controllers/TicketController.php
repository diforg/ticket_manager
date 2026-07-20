<?php

namespace App\Http\Controllers;

use App\Jobs\SendTicketMessageNotificationJob;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\StoreTicketMessageRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Actions\CreateTicketAction;
use Illuminate\Http\RedirectResponse;
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

    public function create(): Response
    {
        return Inertia::render('Tickets/Create');
    }

    public function store(StoreTicketRequest $request, CreateTicketAction $action): RedirectResponse
    {
        $ticket = $action->handle(
            user: $request->user(),
            data: $request->validated(),
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket criado com sucesso.');
    }

    public function show(Ticket $ticket): Response
    {
        $this->authorize('view', $ticket);

        $ticket->load([
            'user:id,name,role',
            'messages' => fn ($query) => $query
                ->with('user:id,name,role')
                ->oldest(),
        ]);

        return Inertia::render('Tickets/Show', [
            'ticket' => [
                'id' => $ticket->id,
                'title' => $ticket->title,
                'description' => $ticket->description,
                'status' => $ticket->status,
                'requester' => $ticket->user?->name,
                'created_at' => $ticket->created_at?->toISOString(),
                'updated_at' => $ticket->updated_at?->toISOString(),
                'messages' => $ticket->messages->map(fn ($message): array => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'created_at' => $message->created_at?->toISOString(),
                    'sender' => [
                        'id' => $message->user?->id,
                        'name' => $message->user?->name,
                        'role' => $message->user?->role,
                    ],
                ])->values()->all(),
            ],
        ]);
    }

    public function storeMessage(StoreTicketMessageRequest $request, Ticket $ticket): RedirectResponse
    {
        $message = $ticket->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $request->validated()['body'],
        ]);

        SendTicketMessageNotificationJob::dispatch($message);

        return back()->with('success', 'Mensagem enviada com sucesso.');
    }

    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update([
            'status' => $request->validated()['status'],
        ]);

        return back()->with('success', 'Status do ticket atualizado com sucesso.');
    }

}
