<?php
namespace App\Utils;
use App\Repositories\UserRepository;
use App\Models\User;

class UserService 
{
  public function getCurrentUser(): ?User {

    if (!session_id())
    {
      session_start();
    }//
    if (!isset($_SESSION['id_user'])){
      return null;
    }
    $userRepo = new UserRepository;
    return $userRepo->findById($_SESSION['id_user']);
    //verifiier si id user existe dans la session
    //var_dump($_SESSION, $user);

    // vérifier si le user et deja trouver ou pas( créer instance)
    //singleton
  }
}