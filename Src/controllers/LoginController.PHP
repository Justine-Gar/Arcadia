<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\utils\PasswordHasher;
use lib\config\Database;

class LoginController extends Controllers
{

  private $userRepository;
  private $passwordHasher;

  public function __construct(UserRepository $userRepository, PasswordHasher $passwordHasher)
  { 
      $db = Database::getInstance();
      parent::__construct();
      $this->userRepository = $userRepository;
      $this->passwordHasher = $passwordHasher;
  }

  public function login() {
      // authentification de user pour ce connecter sur son compte 
      // verifie email
      // verify passeword hash avec PasswordHasher
    /*
      si is_admin => AdminController

      si is_staff => StaffController

      si is_veto => VetoController
    */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
      $email = $_POST['email'] ?? '';
      $password = $_POST['password'] ?? '';

      $user = $this->userRepository->findByEmail($email);

      if ($user && $this->passwordHasher->verifyPassword($password, $user->getPassword())) {
          $this->init_php_session();
          $_SESSION['user_id'] = $user->getId();
          $_SESSION['user_role'] = $user->getRole();

          switch ($user->getRole()) {
              case 'admin':
                  return $this->redirect('/admin/dashboard');
              case 'staff':
                  return $this->redirect('/staff/dashboard');
              case 'veto':
                  return $this->redirect('/veto/dashboard');
              default:
                  return $this->redirect('/dashboard');
          }
      } else {
          return $this->render('login', ['error' => 'Invalid credentials']);
      }
    }

    return $this->render('login');
  }


  public function logout()
  {
    // deconnexcion de l'user
    $this->clean_php_session();
    return $this->redirect('/');
  }

  //inition des session
  private function init_php_session(): bool
  {
    if (!session_id())
    {
      session_start();
      session_regenerate_id();
      return true;
    }
    return false;
  }

  //clear des session
  private function clean_php_session(): void
  {
    session_unset();
    session_destroy();
  }

  public function is_login(): bool {
    //utilisateur et bien connecter
    return isset($_SESSION['user_id']);
  }

  public function is_admin() {
    //utilisateur et bien un administrateur grace à userrepository id_role === 1
    return $this->is_login() && $_SESSION['id_role'] === 1;
  }

  public function is_veto() {
    //utilisateur est bien vétérinaire grace à userrepository id_role === 3
    return $this->is_login() && $_SESSION['id_role'] === 3;
  }

  public function is_staff() {
    //utilisateur est bien un employe grace à userrepository id_role === 2
    return $this->is_login() && $_SESSION['id_role'] === 2;
  }
}