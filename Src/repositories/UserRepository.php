<?php

namespace App\Repositories;

use App\models\User;
use PDO;
use App\utils\PasswordHasher;
use lib\config\database;
use lib\core\Logger;
use PDOException;

class UserRepository extends Repositories
{
  
  private PasswordHasher $passwordHasher;
  private $db;

  public function __construct(database $db)
  {
    $this->passwordHasher = new PasswordHasher();
    $this->db = $db;
  }

  public function findByEmail($email): User
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        // Créer et retourner un objet User
        return new User(
            $userData['id'],
            $userData['email'],
            $userData['password'],
            $userData['firstname'],
            $userData['lastname'],
            $userData['role']
        );
    }

}