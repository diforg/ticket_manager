<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class TicketListingTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUsers;

    public function test_client_sees_only_their_own_tickets(): void
    {
        $client = $this->createUser('client');
        $otherClient = $this->createUser('client');

        Ticket::factory()->for($client)->create([
            'title' => 'Ticket Cliente Autenticado',
            'status' => 'open',
        ]);

        Ticket::factory()->for($otherClient)->create([
            'title' => 'Ticket de Outro Cliente',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($client)->get('/dashboard/client');

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Dashboard/Client'))
            ->assertSee('Ticket Cliente Autenticado')
            ->assertDontSee('Ticket de Outro Cliente');
    }

    public function test_attendant_sees_all_tickets(): void
    {
        $attendant = $this->createUser('attendant');
        $clientOne = $this->createUser('client');
        $clientTwo = $this->createUser('client');

        Ticket::factory()->for($clientOne)->create([
            'title' => 'Ticket Cliente Um',
            'status' => 'open',
        ]);

        Ticket::factory()->for($clientTwo)->create([
            'title' => 'Ticket Cliente Dois',
            'status' => 'resolved',
        ]);

        $response = $this->actingAs($attendant)->get('/dashboard/attendant');

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Dashboard/Attendant'))
            ->assertSee('Ticket Cliente Um')
            ->assertSee('Ticket Cliente Dois');
    }

    public function test_attendant_can_filter_tickets_by_status(): void
    {
        $attendant = $this->createUser('attendant');
        $client = $this->createUser('client');

        Ticket::factory()->for($client)->create([
            'title' => 'Ticket Resolvido',
            'status' => 'resolved',
        ]);

        Ticket::factory()->for($client)->create([
            'title' => 'Ticket Aberto',
            'status' => 'open',
        ]);

        $response = $this->actingAs($attendant)->get('/dashboard/attendant?status=resolved');

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard/Attendant')
                ->where('filters.status', 'resolved'))
            ->assertSee('Ticket Resolvido')
            ->assertDontSee('Ticket Aberto');
    }

    public function test_invalid_status_filter_is_ignored_for_attendant_listing(): void
    {
        $attendant = $this->createUser('attendant');
        $client = $this->createUser('client');

        Ticket::factory()->for($client)->create([
            'title' => 'Ticket Um',
            'status' => 'open',
        ]);

        Ticket::factory()->for($client)->create([
            'title' => 'Ticket Dois',
            'status' => 'resolved',
        ]);

        $response = $this->actingAs($attendant)->get('/dashboard/attendant?status=invalido');

        $response
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard/Attendant')
                ->where('filters.status', null))
            ->assertSee('Ticket Um')
            ->assertSee('Ticket Dois');
    }

    public function test_client_cannot_access_attendant_dashboard(): void
    {
        $client = $this->createUser('client');

        $this->actingAs($client)
            ->get('/dashboard/attendant')
            ->assertForbidden();
    }

    public function test_attendant_cannot_access_client_dashboard(): void
    {
        $attendant = $this->createUser('attendant');

        $this->actingAs($attendant)
            ->get('/dashboard/client')
            ->assertForbidden();
    }
}