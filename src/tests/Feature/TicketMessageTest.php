<?php

namespace Tests\Feature;

use App\Jobs\SendTicketMessageNotificationJob;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class TicketMessageTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsers;

    public function test_client_can_view_own_ticket_with_messages(): void
    {
        $client = $this->createUser('client');
        $attendant = $this->createUser('attendant');

        $ticket = Ticket::factory()->for($client)->create();

        Message::query()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $client->id,
            'body' => 'Mensagem enviada pelo cliente.',
        ]);

        Message::query()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $attendant->id,
            'body' => 'Retorno enviado pelo atendente.',
        ]);

        $response = $this->actingAs($client)->get(route('tickets.show', $ticket));

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tickets/Show')
                ->where('ticket.id', $ticket->id)
                ->where('ticket.messages.0.sender.role', 'client')
                ->where('ticket.messages.1.sender.role', 'attendant'));
    }

    public function test_client_cannot_view_ticket_from_another_client(): void
    {
        $client = $this->createUser('client');
        $otherClient = $this->createUser('client');

        $ticket = Ticket::factory()->for($otherClient)->create();

        $this->actingAs($client)
            ->get(route('tickets.show', $ticket))
            ->assertForbidden();
    }

    public function test_client_can_send_message_to_own_ticket(): void
    {
        config()->set('queue.default', 'database');

        $client = $this->createUser('client');
        $ticket = Ticket::factory()->for($client)->create();

        $response = $this->actingAs($client)->post(route('tickets.messages.store', $ticket), [
            'body' => 'Tenho uma atualização sobre o problema.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('messages', [
            'ticket_id' => $ticket->id,
            'user_id' => $client->id,
            'body' => 'Tenho uma atualização sobre o problema.',
        ]);

        $this->assertDatabaseCount('jobs', 1);

        $payload = DB::table('jobs')->value('payload');
        $decodedPayload = json_decode($payload, true);

        $this->assertNotFalse($payload);
        $this->assertIsArray($decodedPayload);
        $this->assertSame(SendTicketMessageNotificationJob::class, $decodedPayload['displayName']);
        $this->assertSame(SendTicketMessageNotificationJob::class, $decodedPayload['data']['commandName']);
    }

    public function test_attendant_can_send_message_to_any_ticket(): void
    {
        $attendant = $this->createUser('attendant');
        $client = $this->createUser('client');
        $ticket = Ticket::factory()->for($client)->create();

        $response = $this->actingAs($attendant)->post(route('tickets.messages.store', $ticket), [
            'body' => 'Recebemos sua solicitação e vamos prosseguir com a analise.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('messages', [
            'ticket_id' => $ticket->id,
            'user_id' => $attendant->id,
            'body' => 'Recebemos sua solicitação e vamos prosseguir com a analise.',
        ]);
    }

    public function test_client_cannot_send_message_to_ticket_from_another_client(): void
    {
        $client = $this->createUser('client');
        $otherClient = $this->createUser('client');
        $ticket = Ticket::factory()->for($otherClient)->create();

        $response = $this->actingAs($client)->post(route('tickets.messages.store', $ticket), [
            'body' => 'Tentativa de enviar mensagem em ticket alheio.',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('messages', [
            'ticket_id' => $ticket->id,
            'user_id' => $client->id,
            'body' => 'Tentativa de enviar mensagem em ticket alheio.',
        ]);
    }
}
