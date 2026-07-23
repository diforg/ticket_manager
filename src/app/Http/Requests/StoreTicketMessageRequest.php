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
        $isClient = $this->user()?->role === 'client';

        return [
            'body' => ['required', 'string', 'min:2', 'max:5000'],
            'attachments' => $isClient ? ['sometimes', 'array'] : ['prohibited'],
            'attachments.*' => ['file', 'max:10240', 'mimetypes:image/jpeg,image/png,image/gif,image/webp,application/pdf'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Digite uma mensagem antes de enviar.',
            'body.min' => 'A mensagem deve ter pelo menos :min caracteres.',
            'body.max' => 'A mensagem deve ter no máximo :max caracteres.',
            'attachments.prohibited' => 'Somente clientes podem anexar arquivos às mensagens.',
            'attachments.array' => 'Os anexos enviados são inválidos.',
            'attachments.*.file' => 'Cada anexo deve ser um arquivo válido.',
            'attachments.*.max' => 'Cada anexo deve ter no máximo 10 MB.',
            'attachments.*.mimetypes' => 'Envie apenas imagens JPG, JPEG, PNG, GIF, WEBP ou PDF.',
        ];
    }
}
