<?php
namespace src\models;

use InvalidArgumentException;
use src\utils\PasswordHasher;

/**
 * Class représentant un user dans le systeme
 */
class User {

    private ?int $id_user;
    private string $email_user;
    private string $password_user;
    private int $role;

    /** Constructeur de la class User
     * 
     * @param ?int $id_user identifiant de l'user
     * @param string $email_user l'email_user de l'user
     * @param string $password_user le mdp de l'user
     * @param int $role le role de l'utilisateur
     */
    public function __construct(?int $id_user, string $email_user, string $password_user, int $role) 
    {
        $this->setIdUser($id_user);
        $this->setEmailUser($email_user);
        $this->setPasswordUser($password_user);
        $this->setRole($role);
    }

    //GETTERS

    /** Obteien l'id_user user
     * 
     * @return ?int $id_user id_user de l'user
     */
    public function getIdUser(): ?int { return $this->id_user;}

    /** Obtient l'email_user de l'user
     * 
     * @return string $email_user l'email_user de l'user
     */
    public function getEmailUser(): string { return $this->email_user;}

    /** Obtient le mdp de l'user
     * 
     * @return string $password_user le mdp de l'user
     */
    public function getPasswordUser(): string { return $this->password_user;}

    /** Obtient le role de l'user
     * 
     * @return int $role le role de l'user
     */
    public function getRole(): int { return $this->role;}


    //SETTERS

    /** Defini l'id_user de l'user
     * 
     * @param ?int le nouveau identifiant
     * @return ?int l'id_user défini
     * @throws InvalideArgumentEception
     */
    public function setIdUser(?int $id_user): int 
    {   
        if ($id_user !== null && $id_user <= 0) {
            throw new InvalidArgumentException("L'id_user doit être un entier positif");
        }
        return $this->id_user = $id_user;
    }

    /** Defini l'email_user de l'user
     * 
     * @param string $email_user
     * @return string
     * @throws InvalidArgumentException
     */
    public function setEmailUser(string $email_user): void 
    {   
        if (!filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Format d'email_user invalide");
        }
        $this->email_user = $email_user;
    }

    /** Defini le mot de passe de l'user
     * 
     * @param string $password_user
     * @return string $password_user hasshé
     * @throws InvalideArgumentException
     */
    public function setPasswordUser(string $password_user): void 
    {   
        if (strlen($password_user) <= 8) {
            throw new InvalidArgumentException("Le mot de passe doit contenir au moin 8 caractères");
        }
        $this->password_user = (new PasswordHasher())->hashPassword($password_user);
    }

    /** Defini le role de l'user
     * 
     * @param int $role
     * @return int
     * @throws InvalideArgumentException
     */
    public function setRole(int $role): void 
    {   
        $validRoles = [1, 2, 3];
        if (!in_array($role , $validRoles)) {
            throw new InvalidArgumentException("Rôle Invalide");
        }
        $this->role = $role;
    }
}