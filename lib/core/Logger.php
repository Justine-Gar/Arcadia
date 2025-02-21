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
    //Stocker le fuseau horaire
    private static $timezone;

    // Définition des codes de couleur ANSI avec des couleurs plus intuitives
    private static $colors = [
        'INFO' => "\033[0;34m",    // Bleu
        'WARNING' => "\033[1;33m",  // Jaune
        'ERROR' => "\033[0;31m",    // Rouge
        'SUCCESS' => "\033[0;32m",  // Vert
        'RESET' => "\033[0m",       // Réinitialisation
        // Styles supplémentaires
        'BOLD' => "\033[1m",
        'UNDERLINE' => "\033[4m",
        'TIMESTAMP' => "\033[0;36m" // Cyan pour le timestamp
    ];

    // Définition des icônes pour chaque type de message
    private static $icons = [
        'INFO' => '[i]',      // Alternative à ℹ️
        'WARNING' => '[!]',    // Alternative à ⚠️
        'ERROR' => '[x]',      // Alternative à ❌
        'SUCCESS' => '[✓]'     // Alternative à ✅
    ];

    /** Initialise du systeme de logging
     * 
     * @param string $baseLogDir Répertoire de base pour les logs
     * @param string $timezone Fuseau horaire français
     */
    public static function init($baseLogDir, $timezone = 'Europe/Paris')
    {
        self::$baseLogDir = rtrim($baseLogDir, '/');
        self::$timezone = new \DateTimeZone($timezone);
        self::updateLogFile();
    }

    /** Met à jours le fichier de log pour la date actuelle
     * 
     * Créer un répertoire du jour si necessaire
     * @throws \RuntimeException si le répertoire ou fichier ne peuvent pas etre créées
     */
    private static function updateLogFile()
    {
        $today = self::getDate('Y-m-d');
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
     * @param string $level Niveau de log (INFO, WARNING, ERROR, SUCCESS, etc.)
     * @throws \RuntimeException si le fichier de log n'est pas initialisé ou ne peut pas être ouvert
     */
    public static function log($message, $level = 'INFO')
    {
        if (!self::$logFile)
        {
            throw new \RuntimeException('Log file not set. Call Logger::init() first.');
        }

        //vérifions si on est dans le bon dossier(chargement du jour)
        $currentDate = self::getDate('Y-m-d');
        if (dirname(self::$logFile) !== self::$baseLogDir . '/' . $currentDate)
        {
            self::updateLogFile();
        }

        $timestamp = self::getDate('Y-m-d H:i:s');
        $colorStart = self::$colors[$level] ?? self::$colors['INFO'];
        $timestampColor = self::$colors['TIMESTAMP'];
        $bold = self::$colors['BOLD'];
        $colorEnd = self::$colors['RESET'];
        $icon = self::$icons[$level] ?? self::$icons['INFO'];
        
        // Message coloré amélioré pour le terminal avec timestamp en cyan
        $coloredMessage = "{$timestampColor}[{$timestamp}]{$colorEnd} {$colorStart}{$bold}[{$level}]{$colorEnd} {$colorStart}{$message}{$colorEnd}" . PHP_EOL;
        
        // Message sans couleur pour le fichier
        $plainMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;

        $file = fopen(self::$logFile, 'a');
        if ($file === false)
        {
            throw new \RuntimeException("Impossible d'ouvrir le fichier de log : " . self::$logFile);
        }

        // Utilise un verrou exclusif pour éviter les conflits d'écriture
        if (flock($file, LOCK_EX)) {
            fwrite($file, $plainMessage);
            flock($file, LOCK_UN);
            
            // Affichage coloré dans le terminal avec icône
            echo $coloredMessage;
        } else {
            throw new \RuntimeException("Impossible de verrouiller le fichier de log : " . self::$logFile);
        }

        fclose($file);
    }

    //Methode qui utilise le fuseau horaire défini dans le DATETIME
    private static function getDate($format)
    {
        $date = new \DateTime('now', self::$timezone);
        return $date->format($format);
    }

    // Méthodes de commodité pour différents niveaux de log
    /** Enregistre un message de type INFO
     * 
     * @param string $message Message à enregistrer
     */
    public static function info($message)
    {
        self::log($message, 'INFO');
    }

    /** Enregistre un message de type WARNING
     * 
     * @param string $message Message à enregistrer
     */
    public static function warning($message)
    {
        self::log($message, 'WARNING');
    }

    /** Enregistre un message de type ERROR
     * 
     * @param string $message Message à enregistrer
     */
    public static function error($message)
    {
        self::log($message, 'ERROR');
    }

    /** Enregistre un message de type SUCCESS
     * 
     * @param string $message Message à enregistrer
     */
    public static function success($message)
    {
        self::log($message, 'SUCCESS');
    }
}