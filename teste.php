<?php

function hashPassword(string $password): string
{
    $options = [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 3
    ];
    return password_hash($password, PASSWORD_ARGON2ID, $options);
}

// Entrez votre mot de passe ici
$password = "Password789!";

// Hash le mot de passe
$hashedPassword = hashPassword($password);

// Affiche le hash
echo $hashedPassword;
