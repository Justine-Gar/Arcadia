<?php

namespace lib\core;

/**
 * Classe de build pour les fichiers JS sensibles
 */
class JsBuild 
{
    private static $SOURCE_DIR;
    private static $OUTPUT_DIR;
    
    /**
     * Initialise les chemins
     */
    public static function init()
    {
        self::$SOURCE_DIR = dirname(dirname(__DIR__)) . '/assets/js';
        self::$OUTPUT_DIR = dirname(dirname(__DIR__)) . '/public/js/compiled';
        
        // Créer le dossier de sortie s'il n'existe pas
        if (!is_dir(self::$OUTPUT_DIR)) {
            mkdir(self::$OUTPUT_DIR, 0755, true);
        }
    }

    /**
     * Minifie un fichier JavaScript
     */
    private static function minifyJS($source) {
        $source = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $source);
        $source = preg_replace('!//.*!', '', $source);
        $source = preg_replace('/\s+/', ' ', $source);
        return $source;
    }

    /**
     * Ajoute un hash au nom du fichier
     */
    private static function addHashToFilename($filename, $content) {
        $hash = substr(md5($content), 0, 8);
        $info = pathinfo($filename);
        return $info['filename'] . '.' . $hash . '.' . $info['extension'];
    }

    /**
     * Compile tous les fichiers JS sensibles
     */
    public static function buildAll() {
        self::init();

        // Liste des fichiers JS sensibles
        $jsFiles = [
            'avis.js',
            'login.js',
            'logout.js',
            'modify.js',
            'add.js',
            'delete.js'
        ];

        $manifest = [];
        $success = true;

        foreach ($jsFiles as $file) {
            $sourceFile = self::$SOURCE_DIR . '/' . $file;
            if (file_exists($sourceFile)) {
                Logger::info("Compilation de $file...");
                
                $content = file_get_contents($sourceFile);
                $minified = self::minifyJS($content);
                $outputFilename = self::addHashToFilename($file, $minified);
                
                if (file_put_contents(self::$OUTPUT_DIR . '/' . $outputFilename, $minified)) {
                    $manifest[$file] = $outputFilename;
                    Logger::info("✓ $file compilé avec succès");
                } else {
                    Logger::error("Échec de la compilation de $file");
                    $success = false;
                }
            } else {
                Logger::warning("Fichier source non trouvé : $file");
            }
        }

        if ($success) {
            file_put_contents(self::$OUTPUT_DIR . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
            Logger::info("Build terminé avec succès");
        }

        return $success;
    }
}