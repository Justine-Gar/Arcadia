<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\UserRepository;
use lib\core\Response;
use App\Models\User;
use App\Models\Role;
use LDAP\Result;
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
                $messageType = 'success';            
                
            } catch (\Exception $e) {
                Logger::error("Erreur lors de la création du compte: " . $e->getMessage());
                $message = 'Erreur lors de la création du compte';
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
            
            $response = new Response();
            $response->setStatusCode(405);
            $response->json(['success' => false, 'message' => 'Utilisateur non trouvé']);
            return $response;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = Role::from($_POST['role'] ?? '');

            if ($this->userRepository->updateUser($id, $username, $email, $role)) {
                $response = new Response;
                $response->json(['success' => true, 'message' => 'Compte modifié avec succès']);
            } else {
                $response = new Response;
                $response->setStatusCode(500);
                $response->json(['success' => false, 'message' => 'Eurreur lors de la modification du compte']);
            }
        }
        $response = new Response;
        $response->json([
            'success' => true,
            'user' => [
                'id' => $user->getIdUser(),
                'username' => $user->getUsername(),
                'email' => $user->getEmailUser(),
                'role' => $user->getRole()->value
            ]
        ]);

        return $response;

        $data = [
            'title' => 'Modifier un Compte',
            'user' => $user,
            'roles' => Role::cases()
        ];
        return $this->renderAdmin('modifierCompte', $data);
    }

    public function supprimerCompte()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $response = new Response();
            $response->setStatusCode(405);
            $response->json(['success' => false, 'message' => 'Méthode non autorisée']);
            return $response;
        }
    
        $data = json_decode(file_get_contents('php://input'), true);
        $userIds = $data['userIds'] ?? [];
    
        if (empty($userIds)) {
            
            $response = new Response();
            $response->setStatusCode(400);
            $response->json(['success' => false, 'message' => 'Aucun utilisateur sélectionné']);
            return $response;
        }
    
        $deletedCount = 0;
        $errors = [];
    
        foreach ($userIds as $userId) {
            if ($this->userRepository->deleteUser($userId)) {
                $deletedCount++;
            } else {
                $errors[] = "Impossible de supprimer l'utilisateur avec l'ID $userId";
            }
        }
    
        if ($deletedCount > 0) {
            $message = "$deletedCount utilisateur(s) supprimé(s) avec succès";
            if (!empty($errors)) {
                $message .= ". Erreurs : " . implode(", ", $errors);
            }
            $response = new Response();
            $response->json(['success' => true, 'message' => $message]);
        } else {
            $response = new Response();
            $response->setStatusCode(500);
            $response->json(['success' => false, 'message' => 'Erreur lors de la suppression des utilisateurs. ' . implode(", ", $errors)]);
        }
    
        return $response;
    }
}