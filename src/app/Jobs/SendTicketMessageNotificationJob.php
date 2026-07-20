<?php

namespace App\Jobs;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTicketMessageNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Message $message)
    {
    }

    public function handle(): void
    {
        $this->message->loadMissing(['ticket', 'user']);

        Log::info('Notificação assíncrona de mensagem em ticket processada.', [
            'message_id' => $this->message->id,
            'ticket_id' => $this->message->ticket?->id,
            'ticket_title' => $this->message->ticket?->title,
            'sender_id' => $this->message->user?->id,
            'sender_name' => $this->message->user?->name,
        ]);
    }
}
