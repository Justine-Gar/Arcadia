<?php

namespace App\Repositories;

use lib\config\database;

use PDOException;

/** Classe Abstraite Repositories
 * 
 * Sert de Base pour tout mes repositories de l'appli
 * fournit une connexion à la bdd et des methodes communes pour intéragire avec la bdd
 */
abstract class Repositories
{
  //Instanciaion de PDO
  protected database $db;

  /** Constructeur
   * 
   * init la connexion à la bdd lors de la création d'une instance 
   * de n'importe quel classe qui étendra Repositories
   */
  public function __construct()
  {
      try {
          $this->db = database::getInstance();
      } catch (\Throwable $e) {
          // Capture toutes les erreurs, y compris les erreurs fatales
          /*error_log("Erreur détaillée dans Repositories: " . $e->getMessage());
          error_log("Type d'erreur: " . get_class($e));
          error_log("Fichier: " . $e->getFile() . " à la ligne " . $e->getLine());
          error_log("Trace: " . $e->getTraceAsString());*/

          // Rethrow avec plus de détails
          throw new \RuntimeException("Impossible de se connecter à la base de données. Détails: " . $e->getMessage(), 0, $e);
      }
  }

  
  /** Exécuter une requetes SQL préparée
   * 
   * @param string $query la requete sql à éxécuté
   * @param array $params parametre lier à la requetes
   * @return \PDOStatement l'obj apres exécution de requetes
   * @throws \RuntimeException si erreur lors de l'éxécution
   */
  protected function executeQuery(string $query, array $params = []): \PDOStatement 
  {
    try {
      //preparer la requete
      $stmt = $this->db->prepare($query);
      //execute la requetes avec les parametre
      $stmt->execute($params);
      return $stmt;

    } catch (PDOException $e) {
        // En cas d'erreur, on log et on lance une exception
        error_log("Erreur d'exécution de requête: " . $e->getMessage());
        throw new \RuntimeException("Erreur lors de l'exécution de la requête");
    }
  }
}