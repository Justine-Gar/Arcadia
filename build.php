<?php
// Définir les chemins
define('SOURCE_DIR', __DIR__ . '/assets/js');
define('OUTPUT_DIR', __DIR__ . '/public/js/compiled');

// Fonction pour minifier le JS
function minifyJS($source) {
    $source = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $source);
    $source = preg_replace('!//.*!', '', $source);
    $source = preg_replace('/\s+/', ' ', $source);
    return $source;
}

// Fonction pour créer le nom du fichier avec hash
function addHashToFilename($filename, $content) {
    $hash = substr(md5($content), 0, 8);
    $info = pathinfo($filename);
    return $info['filename'] . '.' . $hash . '.' . $info['extension'];
}

// Liste des fichiers à compiler
$jsFiles = [
    'avis.js',
    'login.js',
    'logout.js',
    'modify.js',
    'add.js',
    'delete.js'
];

echo "Début de la compilation...\n\n";

$manifest = [];

foreach ($jsFiles as $file) {
    echo "Traitement de $file...\n";
    
    $sourceFile = SOURCE_DIR . '/' . $file;
    if (file_exists($sourceFile)) {
        $content = file_get_contents($sourceFile);
        $minified = minifyJS($content);
        $outputFilename = addHashToFilename($file, $minified);
        
        if (file_put_contents(OUTPUT_DIR . '/' . $outputFilename, $minified)) {
            $manifest[$file] = $outputFilename;
            echo "✓ $file compilé avec succès -> $outputFilename\n";
        } else {
            echo "✗ Erreur lors de la compilation de $file\n";
        }
    } else {
        echo "✗ Fichier source non trouvé : $file\n";
    }
}

// Sauvegarder le manifest
if (file_put_contents(OUTPUT_DIR . '/manifest.json', json_encode($manifest, JSON_PRETTY_PRINT))) {
    echo "\nManifest créé avec succès !\n";
    echo "Contenu du manifest :\n";
    echo json_encode($manifest, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "\n✗ Erreur lors de la création du manifest\n";
}