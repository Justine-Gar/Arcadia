<?php

namespace App\utils;

class PasswordHasher
{
    public function hashPassword($password)
    {
        $options = [
            'memory_cost' => 65536, // 64 MiB
            'time_cost' => 4,
            'threads' => 3
        ];
        return password_hash($password, PASSWORD_ARGON2ID, $options);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}