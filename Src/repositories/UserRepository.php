<?php

namespace App\Repositories;

use App\models\User;
use PDO;
use App\utils\PasswordHasher;
use lib\core\Logger;
use PDOException;

class UserRepository extends Repositories
{
  
  private PasswordHasher $passwordHasher;

  public function __construct()
  {
    $this->passwordHasher = new PasswordHasher();
  }

  /** Crée un User dans la Base de donnée
   * 
   * @param string $email Email de l'user
   * @param string $password Mdp de l'user
   * @param int $role L'id du role de cette user
   * @return User le nouvel objet User crée
   */
  public function createUser(string $email, string $password, int $role): User
  {
    try {

      $hashedPassword = $this->passwordHasher->hashPassword($password);
      $query = "INSERT INTO `user` (`email`, `password`, `id_role`) VALUES (:email, :password, :id_role)";
      $stmt = $this->connexion()->prepare($query);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindValue(':id_role', $role, PDO::PARAM_INT);

      $stmt->execute();
      $id_user = $this->connexion()->lastInsertId();
      return new User($id_user, $email, $hashedPassword, $role);
    } catch (PDOException $e)
    {

    }
    
  }

}