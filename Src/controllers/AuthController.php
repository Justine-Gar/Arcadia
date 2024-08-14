<?php

namespace App\Controllers;

use App\repositories\UserRepository;

/** Classe AuthController
 * 
 * Gere les opération lié à l'authentification des users
 */
class AuthController 
{
  private UserRepository $userRepository;

  /** Constructeur de AuthController
   * 
   * @param UserRepository $userRepository Instance de UserRepo pour les opération sur les user
   */
  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  /** Gerer la soumission du formulaire de connexion
   * 
   * @param string $email
   * @param string $password
   * @return string Message de succes ou d'erreur
   */
  public function login(string $email, string $password)
  {
    $user = $this->userRepository->authentificate($email, $password);
    
    //vérifier si authentificate résussit
    if ($user) {

      //demarer la session
      session_start();

      $_SESSION['id_user'] = $user->getIdUser();
      $_SESSION['email'] = $user->getEmailUser();
      $_SESSION['role'] = $user->getRole();

      //redirection en function du role
      switch ($user->getRole()) {
        case 1:
          header("Location: /admin");
          break;
        
        case 2:
          header("Location: /employee");
          break;
        
        case 3:
          header("Location: /veterinaire");
          break;
      }
      exit;
    } else {
      //Echec de l'authentification
      return "Echec de l'authentification";
    }
  }

  /** Deconnecter l'utilisateur actuel
   * 
   */
  public function logout() {
    
    //Détruire toute les variable de $_SESSION
    $_SESSION = array();

    //si on veut détuire la sssion complètement
    //cookie de ssion
    if (ini_get("sessionuse_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
      );
    }

    //on détruit la session
    session_destroy();

    //Redirection vers la page d'acceuil
    header("Location: /");
    exit();
  }
}