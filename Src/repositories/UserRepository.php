<?php

namespace App\repositories;

use App\models\User;
use lib\config\database;
use PDO;
use App\utils\PasswordHasher;
use PDOException;

class UserRepository
{
  private PDO $db;
  private PasswordHasher $passwordHasher;

  public function __construct(PDO $db)
  {
    $this->$db = database::getConnection();
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
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindValue(':id_role', $role, PDO::PARAM_INT);

      $stmt->execute();
      $id_user = $this->db->lastInsertId();
      return new User($id_user, $email, $hashedPassword, $role);
    } catch (PDOException $e)
    {
      
    }
    
  }



  /** Authentifie un User
   * 
   * @param string $email Email de l'user
   * @param string $password MDP de l'user
   * @ret
   */
  public function authentificate(string $email, string $password)
  {
    try {
      $query = 'SELECT `id_user`, `email`, `password`, `id_role` FROM `user` WHERE email = :email';
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':email', $email);

      $stmt->execute();

      $userData = $stmt->fetch(PDO::FETCH_ASSOC);
      //vérification si user existe et si mdp est correcte
      if ($userData && $this->passwordHasher->verifyPassword($password, $userData['password'])){
        //mdp correct
        return new User (
          $userData['id_user'],
          $userData['email'],
          $userData['password'],
          $userData['role']
        );
      } else {
        //mdp incorrect
        return false;
      }
    } catch (PDOException $e) {
      // Log l'erreur
      error_log("Erreur lors de l'authentification en BDD : " . $e->getMessage());
      return false;
    }
  }
}