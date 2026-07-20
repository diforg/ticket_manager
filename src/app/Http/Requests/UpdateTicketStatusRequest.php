<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');

        return $ticket instanceof Ticket
            && ($this->user()?->can('update', $ticket) ?? false);
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:' . implode(',', Ticket::validStatuses())],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Selecione um status para o ticket.',
            'status.in' => 'O status selecionado é inválido.',
        ];
    }
}