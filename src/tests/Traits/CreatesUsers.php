<?php

namespace Tests\Traits;

use App\Models\User;

trait CreatesUsers
{
    protected function createUser(string $role): User
    {
        return match ($role) {
            'attendant' => User::factory()->attendant()->create(),
            default => User::factory()->create(['role' => $role]),
        };
    }
}