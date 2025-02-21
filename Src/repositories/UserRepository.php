<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Utils\PasswordHasher;
use lib\core\Logger;
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
   * @param string $username
   * @param string $email
   * @param string $password
   * @param Role $role
   * @return User
   */
  public function createUser(string $username, string $email, string $password, Role $role): User
  {
    try {
      $hashedPassword = $this->passwordHasher->hashPassword($password);
      $query = "INSERT INTO `user` (`username`, `email`, `password`, `role`) VALUES (:username, :email, :password, :role)";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':username', $username, PDO::PARAM_STR);
      $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindValue(':role', $role->value, PDO::PARAM_STR);

      $stmt->execute();
      $id_user = $this->db->lastInsertId();
      return new User($id_user, $username, $email, $hashedPassword, $role);

      //creaet user 
      //Role::Admin;
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la création de l'utilisateur" . $e->getMessage());
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
      Logger::info("Tentative d'authentification pour email: " . $email);
      $query = 'SELECT `id_user`,`username`, `email`, `password`, `role` FROM `user` WHERE email = :email';
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':email', $email);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      Logger::info("Utilisateur trouvé: " . ($user ? "oui" : "non"));
      
      //vérification si user existe et si mdp est correct
      if ($user && $this->passwordHasher->verifyPassword($password, $user['password'])) {
        //$role = $user['role'];
        //$Role = Role::from($role);
        return new User($user['id_user'],$user['username'], $user['email'], $user['password'], Role::from($user['role']));
      } else {

        return false;
      }
    } catch (PDOException $e) {

      Logger::error("Erreur lors de l'authentitification en BDD" . $e->getMessage());
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

      $query = 'SELECT `id_user`, `username`, `email`, `password`, `role` FROM `user` WHERE id_user = :id_user';
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':id_user', $id_user);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      //Condition de sortie immédiate si null
      if (!$user) {
        return null;
      }
      return new User($user['id_user'],$user['username'], $user['email'], $user['password'], Role::from($user['role']));

    } catch (PDOException $e) {

      Logger::error("Identifiant non trouvé" . $e->getMessage());
      throw $e;
    }
  }

  /** Methode pour récupérer tout les user (sauf Admin)
   * 
   * @return array
   */
  public function getAllUsers(): array
  {
    try {
      $query = "SELECT * FROM `user` WHERE role != :admin_role ORDER BY username";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':admin_role', Role::Admin->value, PDO::PARAM_STR);
      $stmt->execute();

      $users = [];
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = new User(
          $row['id_user'],
          $row['username'],
          $row['email'], 
          $row['password'],
          Role::from($row['role'])
        );
      }
      return $users;
    } catch (PDOException $e) {
      Logger::error("Erreur lors de la récupération des User: " . $e->getMessage());
      return[];
    }
  }

  /** Methode pour mettre à jours un User sans mot de passe
   * 
   * @param int $id_user
   * @param string $username
   * @param string $email
   * @param Role $role
   * @return bool
   */
  public function updateUser(int $id_user, string $username, string $email, Role $role): bool
  {
    try {

      $query = "UPDATE `user` SET `username` = :username, `email` = :email, `role` = :role WHERE `id_user` = :id_user";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':username', $username, PDO::PARAM_STR);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':role', $role->value, PDO::PARAM_STR);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      
      return $stmt->execute();

    } catch (PDOException $e) {

      Logger::error("Erreur lors de la mise à jour de l'user " . $e->getMessage());
      return false;
    }
  }

  /** Methode pour mettre à jours un User avec son mot de passe
   * 
   * @param int $id_user
   * @param string $username
   * @param string $email
   * @param Role $role
   * @param string $hashedPassword le mdp hashé
   * @return bool
   */
  public function updateUserWithPassword(int $id_user, string $username, string $email, Role $role, string $hashedPassword): bool
  {
    try {
      $query = "UPDATE `user` 
                SET `username` = :username, 
                    `email` = :email, 
                    `role` = :role,
                    `password` = :password 
                WHERE `id_user` = :id_user";
      
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':username', $username, PDO::PARAM_STR);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':role', $role->value, PDO::PARAM_STR);
      $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      
      return $stmt->execute();

    } catch (PDOException $e) {
        Logger::error("Erreur lors de la mise à jour de l'utilisateur avec mot de passe: " . $e->getMessage());
        return false;
    }
  }
  

  /** Méthode pour supprimer un user
   * 
   * @param int $id_user
   * @return bool
   */
  public function deleteUser(int $id_user): bool
  {
    try{

      $query = "DELETE FROM `user` WHERE `id_user` = :id_user";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);

      return $stmt->execute();
      
    } catch (PDOException $e) {

      Logger::error("Erreur lors de la suppression de l'user: " . $e->getMessage());
      return false;
    }
  }

  /** Methode pour vérifier si user existe
   * 
   * @param string $username
   * @param string $email
   * @return bool
   */
  public function userExists(string $username, string $email): bool
  {
      try {
          $query = "SELECT COUNT(*) FROM `user` WHERE `username` = :username OR `email` = :email";
          $stmt = $this->db->prepare($query);
          $stmt->bindValue(':username', $username, PDO::PARAM_STR);
          $stmt->bindValue(':email', $email, PDO::PARAM_STR);
          $stmt->execute();

          return $stmt->fetchColumn() > 0;
      } catch (PDOException $e) {
          Logger::error("Erreur lors de la vérification de l'existence de l'utilisateur: " . $e->getMessage());
          throw $e;
      }
  }
}
