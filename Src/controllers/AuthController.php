<?php

namespace App\Controllers;

use App\models\Role;
use App\Repositories\UserRepository;
use App\utils\PasswordHasher;
use lib\core\Logger;

class AuthController extends Controllers
{
  private UserRepository $userRepository;
  private PasswordHasher $passwordHasher;
  /** Constructeur
   * 
   */
  public function __construct()
  {
    try {

      $this->userRepository = new UserRepository();
      $this->passwordHasher = new PasswordHasher();

      Logger::info("AuthController initialisation UserRepo et PassHasher success");

    } catch (\Throwable $e) {

      Logger::error("Erreur dans AuthController:" . $e->getMessage());
      Logger::error("Trace: " . $e->getTraceAsString());
      $this->jsonResponse(['succes' => false, 'message' => 'Erreur Interne Authentification']);
      exit;
    }
  }

  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

      Logger::error("Methode non autorisé: " . $_SERVER['REQUEST_METHOD']);
      return $this->jsonResponse(['success' => false, 'message' => 'Méthode non autorisée'], 405);
    }

    $email = filter_input(INPUT_POST, 'emailuser', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {

      Logger::error("identifiant incorrecte");
      return $this->jsonResponse(['success' => false, 'message' => 'Identifiants incorrecte'], 400);
    }

    try {

      $user = $this->userRepository->authenticate($email, $password);

      if ($user) {

        session_start();

        Logger::error("Authentification réussis pour l'email: " . $email);
        $_SESSION['id_user'] = $user->getIdUser();

        $location = match ($user->getRole()) {

          Role::Admin => '/admin',
          Role::Staff => '/employe',
          Role::Veto => '/veterinaire',
          default => '/'
        };

        return $this->jsonResponse(['success' => true, 'redirect' => $location]);
        
      } else {

        return $this->jsonResponse(['success' => false, 'message' => 'Authentification à échouée'], 401);
      }

    } catch (\Throwable $e) {

      Logger::error("Erreur lors de l'authentification: " . $e->getMessage());
      Logger::error("Trace: " . $e->getTraceAsString());
      return $this->jsonResponse(['success' => false, 'message' => 'Erreur lors de la tentative de connexion'], 500);

    }
  }

  private function jsonResponse($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
  }
}