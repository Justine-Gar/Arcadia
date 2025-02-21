<?php

namespace App\Controllers;

use App\Controllers\Controllers;
use lib\core\Mail;
use lib\core\Response;

class ContactController extends Controllers
{
    private $mail;

    public function __construct() {
        $this->mail = new Mail();
    }

    public function index()
    {
        $data = [
            'title' => 'Contact',
        ];

        return $this->render('contact', $data);
    }

    public function handleContact() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return new Response(['error' => 'Méthode non autorisée'], 405);
        }

        // Validation des champs
        if (empty($_POST['title_contact']) || empty($_POST['mail_contact']) || empty($_POST['description_contact'])) {
            return new Response(['error' => 'Tous les champs sont requis'], 400);
        }

        // Validation basique de l'email
        if (!filter_var($_POST['mail_contact'], FILTER_VALIDATE_EMAIL)) {
            return new Response(['error' => 'Adresse email invalide'], 400);
        }

        $mailData = [
            'title' => $_POST['title_contact'],
            'email' => $_POST['mail_contact'],
            'description' => $_POST['description_contact']
        ];

        if ($this->mail->save($mailData)) {
            return new Response(['success' => 'Votre message a bien été envoyé !'], 200);
        }

        return new Response(['error' => 'Une erreur est survenue lors de l\'envoi du message'], 500);
        
    }
}