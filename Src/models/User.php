<?php
namespace src\models;

use InvalidArgumentException;
use src\utils\PasswordHasher;

/**
 * Class représentant un user dans le systeme
 */
class User {

    private ?int $id;
    private string $email;
    private string $password;
    private int $role;

    /** Constructeur de la class User
     * 
     * @param ?int $id identifiant de l'user
     * @param string $email l'email de l'user
     * @param string $password le mdp de l'user
     * @param int $role le role de l'utilisateur
     */
    public function __construct(?int $id, string $email, string $password, int $role) 
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    //GETTERS

    /** Obteien l'id user
     * 
     * @return ?int $id id de l'user
     */
    public function getId(): ?int { return $this->id;}

    /** Obtient l'email de l'user
     * 
     * @return string $email l'email de l'user
     */
    public function getEmail(): string { return $this->email;}

    /** Obtient le mdp de l'user
     * 
     * @return string $password le mdp de l'user
     */
    public function getPassword(): string { return $this->password;}

    /** Obtient le role de l'user
     * 
     * @return int $role le role de l'user
     */
    public function getRole(): int { return $this->role;}


    //SETTERS

    /** Defini l'id de l'user
     * 
     * @param ?int le nouveau identifiant
     * @return ?int l'id défini
     * @throws InvalideArgumentEception
     */
    public function setId(?int $id): int 
    {   
        if ($id !== null && $id <= 0) {
            throw new InvalidArgumentException("L'ID doit être un entier positif");
        }
        return $this->id = $id;
    }

    /** Defini l'email de l'user
     * 
     * @param string $email
     * @return string
     * @throws InvalidArgumentException
     */
    public function setEmail(string $email): string 
    {   
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Format d'email invalide");
        }
        return $this->email = $email;
    }

    /** Defini le mot de passe de l'user
     * 
     * @param string $password
     * @return string $password hasshé
     * @throws InvalideArgumentException
     */
    public function setPassword(string $password): string 
    {   
        if (strlen($password) <= 8) {
            throw new InvalidArgumentException("Le mot de passe doit contenir au moin 8 caractères");
        }
        $this->password = (new PasswordHasher())->hashPassword($password);
        return $this->password;
    }

    /** Defini le role de l'user
     * 
     * @param int $role
     * @return int
     * @throws InvalideArgumentException
     */
    public function setRole(int $role): int 
    {   
        $validRoles = [1, 2, 3];
        if (!in_array($role , $validRoles)) {
            throw new InvalidArgumentException("Rôle Invalide");
        }
        return $this->role = $role;
    }
}