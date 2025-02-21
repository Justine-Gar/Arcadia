<?php
namespace App\Controllers;

use App\Controllers\Controllers;
use lib\core\Mail;
use lib\core\Logger;
use lib\core\Response;

class MailController extends Controllers
{
  private $mail;

  public function __construct() {
      $this->mail = new Mail();
  }

  public function gestionMails() {
      $data = [
          'title' => 'Boite Mail',
          'mails' => $this->mail->getAll()
      ];

      return $this->renderAdmin('gestionMails', $data);
  }

  public function markAsRead($id) {
    if ($this->mail->markAsRead($id)) {
        return new Response([
            'success' => 'Message marqué comme lu'
        ], 200);
    } 
    
    return new Response([
        'error' => 'Erreur lors de la mise à jour'
    ], 400);
  }

  public function deleteMail($id) {
    if ($this->mail->delete($id)) {
        return new Response([
            'success' => 'Message supprimé'
        ], 200);
    }
    
    return new Response([
        'error' => 'Erreur lors de la suppression'
    ], 400);
  }
}