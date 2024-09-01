<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Role;
use lib\core\Logger;

class UserController extends Controllers
{
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function gestionComptes()
    {
        $users = $this->userRepository->getAllUsers();
        
        $data = [
            'title' => 'Gestion des Comptes',
            'users' => $users,
            'roles' => Role::cases()
        ];
        return $this->renderAdmin('gestionComptes', $data);
    }

    public function ajouterCompte()
    {
        $message = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = Role::from($_POST['role'] ?? '');

            try {
                $newUser = $this->userRepository->createUser($username, $email, $password, $role);
                $message = 'Compte créé avec succès';
                $messageType = 'success';            } catch (\Exception $e) {
                
            } catch (\Exception $e) {
                $message = 'Erreur lors de la création du compte: ' . $e->getMessage();
                $messageType = 'error';
            }
        }

        $users = $this->userRepository->getAllUsers();
        
        $data = [
            'title' => 'Gestion des Comptes',
            'users' => $users,
            'roles' => Role::cases(),
            'message' => $message,
            'messageType' => $messageType
        ];

        Logger::error('Méthode gestionComptes appelée. Méthode: ' . $_SERVER['REQUEST_METHOD']);
        Logger::error('Données passées à la vue: ' . print_r($data, true));

        return $this->renderAdmin('gestionComptes', $data);
    }

    public function modifierCompte($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = Role::from($_POST['role'] ?? '');

            if ($this->userRepository->updateUser($id, $username, $email, $role)) {

            } else {

            }
        }

        $data = [
            'title' => 'Modifier un Compte',
            'user' => $user,
            'roles' => Role::cases()
        ];
        return $this->renderAdmin('modifierCompte', $data);
    }

    public function supprimerCompte($id)
    {
        if ($this->userRepository->deleteUser($id)) {

        } else {

        }
    }
}