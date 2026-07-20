<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class TicketCreationTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsers;

    public function test_client_can_create_a_ticket(): void
    {
        $client = $this->createUser('client');

        $response = $this->actingAs($client)->post('/tickets', [
            'title' => 'Problema ao acessar o sistema',
            'description' => 'Não consigo fazer login desde a última atualização.',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $ticket = Ticket::query()->where('user_id', $client->id)->first();

        $this->assertNotNull($ticket, 'Ticket deve ter sido criado no banco de dados.');

        $response->assertRedirect(route('tickets.show', $ticket));

        $this->assertDatabaseHas('tickets', [
            'user_id' => $client->id,
            'title' => 'Problema ao acessar o sistema',
            'description' => 'Não consigo fazer login desde a última atualização.',
            'status' => Ticket::STATUS_OPEN,
        ]);
    }

    public function test_attendant_cannot_create_a_ticket(): void
    {
        $attendant = $this->createUser('attendant');

        $response = $this->actingAs($attendant)->post('/tickets', [
            'title' => 'Problema ao acessar o sistema',
            'description' => 'Não consigo fazer login desde a última atualização.',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('tickets', [
            'user_id' => $attendant->id,
        ]);
    }
}