<?php
// lib/core/Mail.php
namespace lib\core;

class Mail {
  private $storageDirectory;

  public function __construct() {
      $this->storageDirectory = __DIR__ . '/../../lib/storage/mails/';
      $this->initStorage();
  }

  private function initStorage() {
      if (!is_dir($this->storageDirectory)) {
          mkdir($this->storageDirectory, 0777, true);
      }
  }

  public function save($mailData) {
      $mail = [
          'id' => uniqid(),
          'date' => date('Y-m-d H:i:s'),
          'title' => $mailData['title'],
          'email' => $mailData['email'],
          'description' => $mailData['description'],
          'status' => 'unread'
      ];

      $filename = $this->storageDirectory . $mail['id'] . '.json';
      return file_put_contents($filename, json_encode($mail)) !== false;
  }

  public function getAll() {
      $mails = [];
      
      if (is_dir($this->storageDirectory)) {
          $files = scandir($this->storageDirectory);
          foreach ($files as $file) {
              if ($file != '.' && $file != '..') {
                  $mailContent = file_get_contents($this->storageDirectory . $file);
                  if ($mailContent !== false) {
                      $mails[] = json_decode($mailContent, true);
                  }
              }
          }
      }

      // Tri par date décroissante
      usort($mails, function($a, $b) {
          return strtotime($b['date']) - strtotime($a['date']);
      });

      return $mails;
  }

  public function markAsRead($id) {
      $filename = $this->storageDirectory . $id . '.json';
      if (file_exists($filename)) {
          $mail = json_decode(file_get_contents($filename), true);
          $mail['status'] = 'read';
          return file_put_contents($filename, json_encode($mail)) !== false;
      }
      return false;
  }

  public function delete($id) {
      $filename = $this->storageDirectory . $id . '.json';
      if (file_exists($filename)) {
          return unlink($filename);
      }
      return false;
  }
}