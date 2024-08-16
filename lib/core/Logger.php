<?php

namespace lib\core;

/** Classe Logger
 * 
 * Création d'un systeme de journalisation qui écris des logs organiser par date
 */
class Logger 
{   
    //Répertoire de base ou les logs seront stocker
    private static $baseLogDir;
    //Chemin complet vers le fichier de log actuel
    private static $logFile;

    /** Initialise du systeme de logging
     * 
     * @param string string $baseLogDir Répertoire de base pour les logs
     */
    public static function init($baseLogDir)
    {
        self::$baseLogDir = rtrim($baseLogDir, '/');
        self::updateLogFile();
    }

    /** Met à jours le fichier de log pour la date actuell
     * 
     * Créer un répertoire du jour si necessaire
     * @throws \RuntimeException si le répertoire ou fichier ne peuvent pas etre créées
     */
    private static function updateLogFile()
    {
        $today = date('Y-m-d');
        $todayLogDir = self::$baseLogDir . '/' . $today;

        //Créer un répertoire du jours s'il n'existe pas
        if (!is_dir($todayLogDir))
        {
            if (!mkdir($todayLogDir, 0777, true))
            {
                throw new \RuntimeException("Impossible de créer le dossier de logs du jour : $todayLogDir");
            }
        }
        
        self::$logFile = $todayLogDir . '/log.txt';

        //vérifie les permission d'écriture
        if (!is_writable(self::$logFile) && !is_writable($todayLogDir))
        {
            throw new \RuntimeException("Le fichier de log n'est pas accessible en écriture : " . self::$logFile);
        }
    }

    /** Enregistre un message dans le fichier log
     * 
     * @param string $message Message à enregistrer
     * @param string $level Niveau de log (INFO, WARNING, ERROR, etc.)
     * @throws \RuntimeException si le fichier de log n'est pas initialisé ou ne peut pas être ouver
     */
    public static function log($message, $level= 'INFO')
    {
        if (!self::$logFile)
        {
            throw new \RuntimeException('Log file not set. Call Logger::init() first.');
        }

        //vérifions si on est dans le bon dossier(chargement du jour)
        $currentDate = date('Y-m-d');
        if (dirname(self::$logFile) !== self::$baseLogDir . '/' . $currentDate)
        {
            self::updateLogFile();
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;

        $file = fopen(self::$logFile, 'a');
        if ($file === false)
        {
            throw new \RuntimeException("Impossible d'ouvrir le fichier de log : " . self::$logFile);
        }

        // Utilise un verrou exclusif pour éviter les conflits d'écriture
        if (flock($file, LOCK_EX)) {
            fwrite($file, $logMessage);
            flock($file, LOCK_UN);
        } else {
            throw new \RuntimeException("Impossible de verrouiller le fichier de log : " . self::$logFile);
        }

        fclose($file);
    }

    // Méthodes de commodité pour différents niveaux de log
    public static function info($message)
    {
        self::log($message, 'INFO');
    }

    public static function warning($message)
    {
        self::log($message, 'WARNING');
    }

    public static function error($message)
    {
        self::log($message, 'ERROR');
    }
}
