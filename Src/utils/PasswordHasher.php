<?php

namespace App\Utils;

class PasswordHasher
{
    public function hashPassword(string $password): string
    {
        $options = [
            'memory_cost' => 65536, // 64 MiB
            'time_cost' => 4,
            'threads' => 3
        ];
        return password_hash($password, PASSWORD_ARGON2ID, $options);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}