<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');

        return $ticket instanceof Ticket
            && ($this->user()?->can('message', $ticket) ?? false);
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Digite uma mensagem antes de enviar.',
            'body.min' => 'A mensagem deve ter pelo menos :min caracteres.',
            'body.max' => 'A mensagem deve ter no máximo :max caracteres.',
        ];
    }
}
