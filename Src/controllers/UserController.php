<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use App\Repositories\UserRepository;
use lib\core\Response;
use App\Models\User;
use App\Models\Role;
use lib\core\Logger;
use App\Utils\PasswordHasher;

class UserController extends Controllers
{
    private $userRepository;
    private $passwordHasher;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->passwordHasher = new PasswordHasher();
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

            $jsonData = json_decode(file_get_contents('php://input'), true);

            $username = $jsonData['add_username'] ?? '';
            $email = $jsonData['add_email'] ?? '';
            $password = $jsonData['add_password'] ?? '';
            $role = Role::from($jsonData['add_role'] ?? '');

            // Vérifier si l'utilisateur existe déjà
            if ($this->userRepository->userExists($username, $email)) {
                $response = new Response();
                $response->setStatusCode(400);
                $response->json(['success' => false, 'message' => 'Un utilisateur avec ce nom ou cet email existe déjà']);
                return $response;

            } else {

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

            $response = new Response();
            $response->json([
            'success' => $messageType === 'success',
            'message' => $message,
            'messageType' => $messageType
            ]);

            return $response;

        }

        $users = $this->userRepository->getAllUsers();
        
        $data = [
            'title' => 'Gestion des Comptes',
            'users' => $users,
            'roles' => Role::cases(),
            'message' => $message,
            'messageType' => $messageType
        ];

        //Logger::error('Méthode gestionComptes appelée. Méthode: ' . $_SERVER['REQUEST_METHOD']);
        //Logger::error('Données passées à la vue: ' . print_r($data, true));

        return $this->renderAdmin('gestionComptes', $data);
    }

    public function modifierCompte($id = null)
    {
        // Si c'est une requête GET, on renvoie juste les infos de l'utilisateur
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if ($id === null) {
                $response = new Response();
                $response->setStatusCode(400);
                $response->json(['success' => false, 'message' => 'ID utilisateur manquant']);
                return $response;
            }

            $user = $this->userRepository->findById($id);
            if (!$user) {
                $response = new Response();
                $response->setStatusCode(404);
                $response->json(['success' => false, 'message' => 'Utilisateur non trouvé']);
                return $response;
            }

            $response = new Response();
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
        }
        
        // Si c'est une requête POST, on traite la modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $jsonData = json_decode(file_get_contents('php://input'), true);
                
                if (!$jsonData) {
                    throw new \Exception('Données invalides');
                }
                
                $id = $jsonData['id'];
                $user = $this->userRepository->findById($id);
                if (!$user) {
                    $response = new Response();
                    $response->setStatusCode(404);
                    $response->json(['success' => false, 'message' => 'Utilisateur non trouvé']);
                    return $response;
                }

                $username = $jsonData['modify_username'] ?? '';
                $email = $jsonData['modify_email'] ?? '';
                $role = Role::from($jsonData['modify_role'] ?? '');
                $password = $jsonData['modify_password'] ?? null;

                if ($password) {
                    // Si un nouveau mot de passe est fourni
                    $plainPassword = $password; // Pour l'email
                    $hashedPassword = $this->passwordHasher->hashPassword($password);
                    
                    // Simuler l'envoi d'email (à remplacer par votre système d'email)
                    Logger::info("Email envoyé à {$email} avec le mot de passe: {$plainPassword}");
                    
                    $success = $this->userRepository->updateUserWithPassword($id, $username, $email, $role, $hashedPassword);
                } else {
                    $success = $this->userRepository->updateUser($id, $username, $email, $role);
                }

                if ($success) {
                    $response = new Response();
                    $response->json([
                        'success' => true, 
                        'message' => 'Compte modifié avec succès'
                    ]);
                } else {
                    throw new \Exception('Erreur lors de la modification du compte');
                }
            } catch (\Exception $e) {
                $response = new Response();
                $response->setStatusCode(500);
                $response->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            return $response;
        }

        // Si ce n'est ni GET ni POST
        $response = new Response();
        $response->setStatusCode(405);
        $response->json(['success' => false, 'message' => 'Méthode non autorisée']);
        return $response;
    }

    public function supprimerCompte()
    {
        //vérification de la methode HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = new Response();
            $response->setStatusCode(405);
            $response->json([
                'success' => false,
                'message' => 'Méthode non autorisé'
            ]);
            return $response;

        }

        try {
            //récupérer et valider les données
            $jsonData = json_decode(file_get_contents('php://input'), true);
            if (!isset($jsonData['userIds']) || !is_array($jsonData['userIds'])) {
                throw new \Exception('Données de suppréssion invalides');
            }

            $userIds = array_map('intval', $jsonData['userIds']);
            if (empty($userIds)) {
                throw new \Exception('Aucun utilisateur sélectionné');
            }

            //vérifie que les users existent
            $nonExistentUsers = [];
            foreach ($userIds as $userId) {
                if(!$this->userRepository->findById($userId)) {
                    $nonExistentUsers[] = $userId;
                }
            }

            if(!empty($nonExistentUsers)) {
                throw new \Exception('Les utilisateurs n\'existent pas : ' . implode(', ', $nonExistentUsers));
            }

            // Tentative de suppression
            $successCount = 0;
            $errors = [];

            foreach ($userIds as $userId) {
                try {
                    if ($this->userRepository->deleteUser($userId)) {
                        $successCount++;
                    } else {
                        $errors[] = "Échec de la suppression de l'utilisateur {$userId}";
                    }
                } catch (\Exception $e) {
                    Logger::error("Erreur lors de la suppression de l'utilisateur {$userId}: " . $e->getMessage());
                    $errors[] = "Erreur lors de la suppression de l'utilisateur {$userId}";
                }
            }

            // Préparation de la réponse
            if ($successCount > 0) {
                // Si un seul utilisateur
                if ($successCount === 1) {
                    $user = $this->userRepository->findById($userIds[0]);
                    $username = $user ? $user->getUsername() : 'inconnu';
                    $message = "L'utilisateur \"{$username}\" a été supprimé avec succès";
                } else {
                    // Si plusieurs utilisateurs
                    $usernames = [];
                    foreach ($userIds as $userId) {
                        $user = $this->userRepository->findById($userId);
                        if ($user) {
                            $usernames[] = $user->getUsername();
                        }
                    }
                    $message = "Les utilisateurs suivants ont été supprimés : \"" . implode('", "', $usernames) . "\"";
                }

                if (!empty($errors)) {
                    $message .= ". Erreurs : " . implode(", ", $errors);
                }

                $response = new Response();
                $response->json([
                    'success' => true,
                    'message' => $message,
                    'deletedCount' => $successCount,
                    'errors' => $errors
                ]);
            } else {
                throw new \Exception('Aucun utilisateur n\'a pu être supprimé. ' . implode(", ", $errors));
            }

        } catch (\Exception $e) {
            Logger::error("Erreur lors de la suppression des utilisateurs: " . $e->getMessage());
            $response = new Response();
            $response->setStatusCode(500);
            $response->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        return $response;
    }

    
}