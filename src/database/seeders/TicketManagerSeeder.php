<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TicketManagerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();

        $attendant = User::query()->create([
            'name' => 'Atendente Suporte',
            'email' => 'atendente@teste.local',
            'password' => Hash::make('password'),
            'role' => 'attendant',
        ]);

        $customerOne = User::query()->create([
            'name' => 'Cliente Um',
            'email' => 'cliente1@teste.local',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        $customerTwo = User::query()->create([
            'name' => 'Cliente Dois',
            'email' => 'cliente2@teste.local',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        $ticketOneId = DB::table('tickets')->insertGetId([
            'user_id' => $customerOne->id,
            'title' => 'Erro ao gerar boleto',
            'description' => 'Ao clicar em gerar boleto, o sistema retorna erro 500.',
            'status' => 'open',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $ticketTwoId = DB::table('tickets')->insertGetId([
            'user_id' => $customerTwo->id,
            'title' => 'Dificuldade para atualizar cadastro',
            'description' => 'Nao consigo salvar alteracoes no perfil da empresa.',
            'status' => 'in_progress',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('tickets')->insert([
            'user_id' => $customerOne->id,
            'title' => 'Ajuste concluido no acesso ao portal',
            'description' => 'O problema de acesso foi resolvido apos ajuste de permissoes.',
            'status' => 'resolved',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $messageOneId = DB::table('messages')->insertGetId([
            'ticket_id' => $ticketOneId,
            'user_id' => $customerOne->id,
            'body' => 'O erro acontece desde ontem, em qualquer navegador.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('messages')->insert([
            'ticket_id' => $ticketOneId,
            'user_id' => $attendant->id,
            'body' => 'Obrigado pelo relato. Estamos analisando os logs para identificar a causa.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('messages')->insert([
            'ticket_id' => $ticketTwoId,
            'user_id' => $attendant->id,
            'body' => 'Identificamos que o problema ocorre por falta de permissao no perfil atual.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('messages')->insert([
            'ticket_id' => $ticketTwoId,
            'user_id' => $customerTwo->id,
            'body' => 'Perfeito, vou validar com o usuario administrador e retorno.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('attachments')->insert([
            'message_id' => $messageOneId,
            'file_path' => 'attachments/print-erro-boleto-001.png',
            'original_name' => 'print-erro-boleto.png',
            'mime_type' => 'image/png',
            'size' => 245123,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
