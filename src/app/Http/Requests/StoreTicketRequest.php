<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Ticket::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Informe um título para o ticket.',
            'title.min' => 'O título deve ter pelo menos :min caracteres.',
            'title.max' => 'O título deve ter no máximo :max caracteres.',
            'description.required' => 'Informe uma descrição para o ticket.',
            'description.min' => 'A descrição deve ter pelo menos :min caracteres.',
            'description.max' => 'A descrição deve ter no máximo :max caracteres.',
        ];
    }
}