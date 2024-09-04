<?php

namespace App\Controllers;

use App\models\Role;
use App\Repositories\UserRepository;
use App\utils\PasswordHasher;
use lib\core\Response;
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
      $response = new Response();
      $response->json(['succes' => false, 'message' => 'Erreur Interne Authentification']);
      return $response;
      exit;
    }
  }

  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

      Logger::error("Methode non autorisé: " . $_SERVER['REQUEST_METHOD']);

      $response = new Response();
      $response->setStatusCode(405);
      $response->json(['success' => false, 'message' => 'Méthode non autorisée']);
      return $response;

    }

    $email = filter_input(INPUT_POST, 'emailuser', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {

      Logger::error("identifiant incorrecte");

      $response = new Response();
      $response->setStatusCode(400);
      $response->json(['success' => false, 'message' => 'Identifiants incorrecte']);
      return $response;
    }

    try {

      $user = $this->userRepository->authenticate($email, $password);

      if ($user) {

        session_start();

        Logger::error("Authentification réussis pour l'email: " . $email);
        $_SESSION['id_user'] = $user->getIdUser();
        $_SESSION['user_role'] = $user->getRole();

        //cookie 30 jours
        setcookie("user_logged", "true", time() + (30 * 24 * 60 * 60), "/", "", true, true);

        $location = match ($user->getRole()) {

          Role::Admin => '/admin',
          Role::Staff => '/employe',
          Role::Veto => '/veto',
          default => '/'
        };

        $response = new Response();
        $response->json(['success' => true, 'redirect' => $location]);
        return $response;

      } else {

        $response = new Response();
        $response->setStatusCode(401);
        $response->json(['success' => false, 'message' => 'Authentification à échouée']);
        return $response;
      }

    } catch (\Throwable $e) {

      Logger::error("Erreur lors de l'authentification: " . $e->getMessage());
      Logger::error("Trace: " . $e->getTraceAsString());

      $response = new Response();
      $response->setStatusCode(500);
      $response->json(['success' => false, 'message' => 'Erreur lors de la tentative de connexion']);
      return $response;

    }
  }

  public function logout()
  {
    session_start();

    // Détruire la session
    session_destroy();

    // Supprimer le cookie si vous en utilisez un
    if (isset($_COOKIE['user_logged'])) {
      setcookie("user_logged", "", time() - 3600, "/");
    }

    Logger::info("Déconnexion réussie");

    $response = new Response();
    $response->json([
      'success' => true,
      'message' => 'Déconnexion réussie',
      'redirect' => '/'
    ]);
    return $response;
  }
}
