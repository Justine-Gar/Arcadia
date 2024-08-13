<?php

namespace App\repositories;

use lib\config\database;
use PDO;

/**
 * Class de base pour tous les repositories
 * Interaction avec la base de données
 */
abstract class Repository 
{
  protected PDO $db;

  /** 
   * Constructeur : initialise la connexion ç la base de données
   */
  public function __construct()
  {
    $this->db = database::getConnection();
  }

  //Ajout de method pour tout les repositories

  /** Exécuter une requetes SQL préparée
   * 
   * @param string $query la requete exécuter
   * @param array $params lier à la requete
   * @return \PDOStatemnt l'objet statement apres affectation
   */
  protected function execute($query, $params = [])
  {
    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }

  /** Exécute une requetes et return 1 ligne de resultat
   * 
   * @param string $query
   * @param array $params
   * @return array|false
   */
  protected function fetch($query, $params = [])
  {
    return $this->execute($query, $params)->fetch();
  }

  /** Exécute une requetes et return les lignes de resultat
   * 
   * @param string $query
   * @param array $params
   * @return array
   */
  protected function fetchAll($query, $params = [])
  {
    return $this->execute($query, $params)->fetchAll();
  }
}