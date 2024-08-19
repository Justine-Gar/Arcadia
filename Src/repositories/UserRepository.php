<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Utils\PasswordHasher;
use PDO;
use PDOException;

class UserRepository extends Repositories
{

  private PasswordHasher $passwordHasher;

  public function __construct()
  {
    parent::__construct();
    $this->passwordHasher = new PasswordHasher();
  }

  /** Methode de création d'un utilisateur
   * 
   * @param string $email
   * @param string $password
   * @param Role $role
   * @return User
   */
  public function createUser(string $email, string $password, Role $role): User
  {
    try {
      $hashedPassword = $this->passwordHasher->hashPassword($password);
      $query = "INSERT INTO `user` (`email`, `password`, `role`) VALUES (:email, :password, :role)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindValue(':role', $role->value, PDO::PARAM_STR);

      $stmt->execute();
      $id_user = $this->db->lastInsertId();
      return new User($id_user, $email, $hashedPassword, $role);

      //creaet user 
      //Role::Admin;
    } catch (PDOException $e) {
      throw $e;
    }
  }

  /** Methode d'authentification d'un user
   * 
   * @param string $email Email d'un user
   * @param string $password mot de passe d'un user
   * @return User|false
   */
  public function authenticate(string $email, string $password): User|false
  {
    try {

      $query = 'SELECT `id_user`, `email`, `password`, `role` FROM `user` WHERE email = :email';
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':email', $email);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      //vérification si user existe et si mdp est correct
      if ($user && $this->passwordHasher->verifyPassword($password, $user['password'])) {
        //$role = $user['role'];
        //$Role = Role::from($role);
        return new User($user['id_user'], $user['email'], $user['password'], Role::from($user['role']));
      } else {

        return false;
      }
    } catch (PDOException $e) {

      error_log("Erreur lors de l'authentitification en BDD" . $e->getMessage());
      throw $e;
    }
  }

  /** Method trouver un id User
   * 
   * @param int $id_user 
   * @return ?User
   */
  public function findById(int $id_user): ?User
  {
    try {

      $query = 'SELECT `id_user`, `email`, `password`, `role` FROM `user` WHERE id_user = :id_user';
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':id_user', $id_user);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      //Condition de sortie immédiate si null
      if (!$user) {
        return null;
      }
      return new User($user['id_user'], $user['email'], $user['password'], Role::from($user['role']));

    } catch (PDOException $e) {

      error_log("Erreur lors de l'authentitification en BDD" . $e->getMessage());
      throw $e;
    }
  }
}
