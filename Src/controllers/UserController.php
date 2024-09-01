<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Role;

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = Role::from($_POST['role'] ?? '');

            try {
                $newUser = $this->userRepository->createUser($username, $email, $password, $role);
                $this->redirect('/admin/comptes', ['success' => 'Compte créé avec succès']);
            } catch (\Exception $e) {
                $this->redirect('/admin/comptes/ajouter', ['error' => 'Erreur lors de la création du compte']);
            }
        }

        $data = [
            'title' => 'Ajouter un Compte',
            'roles' => Role::cases()
        ];
        return $this->renderAdmin('gestionComptes', $data);
    }

    public function modifierCompte($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            $this->redirect('/admin/comptes', ['error' => 'Utilisateur non trouvé']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = Role::from($_POST['role'] ?? '');

            if ($this->userRepository->updateUser($id, $username, $email, $role)) {
                $this->redirect('/admin/comptes', ['success' => 'Compte modifié avec succès']);
            } else {
                $this->redirect('/admin/comptes/modifier/' . $id, ['error' => 'Erreur lors de la modification du compte']);
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
            $this->redirect('/admin/comptes', ['success' => 'Compte supprimé avec succès']);
        } else {
            $this->redirect('/admin/comptes', ['error' => 'Erreur lors de la suppression du compte']);
        }
    }
}