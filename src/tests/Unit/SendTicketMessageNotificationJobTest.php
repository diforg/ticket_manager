<?php

namespace Tests\Unit;

use App\Jobs\SendTicketMessageNotificationJob;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SendTicketMessageNotificationJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_logs_notification_details(): void
    {
        Log::spy();

        $user = User::factory()->create();
        $ticket = Ticket::factory()->for($user)->create();
        $message = Message::query()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => 'Mensagem para validar o job.',
        ]);

        (new SendTicketMessageNotificationJob($message))->handle();

        Log::shouldHaveReceived('info')
            ->once()
            ->with(
                'Notificação assíncrona de mensagem em ticket processada.',
                [
                    'message_id' => $message->id,
                    'ticket_id' => $ticket->id,
                    'ticket_title' => $ticket->title,
                    'sender_id' => $user->id,
                    'sender_name' => $user->name,
                ]
            );
    }
}
