<?php
echo "Vérification spécifique de delete.js\n\n";

// 1. Vérifier le fichier source
$sourcePath = __DIR__ . '/assets/js/delete.js';
echo "1. Fichier source :\n";
if (file_exists($sourcePath)) {
    echo "✓ delete.js existe dans assets/js/\n";
    echo "Contenu du fichier : \n";
    echo substr(file_get_contents($sourcePath), 0, 100) . "...\n"; // Affiche les 100 premiers caractères
} else {
    echo "✗ delete.js n'existe pas dans assets/js/\n";
}

// 2. Vérifier le manifest
$manifestPath = __DIR__ . '/public/js/compiled/manifest.json';
echo "\n2. Manifest :\n";
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
    echo "Contenu du manifest :\n";
    print_r($manifest);
    
    if (isset($manifest['delete.js'])) {
        echo "✓ delete.js est présent dans le manifest\n";
        $compiledPath = __DIR__ . '/public/js/compiled/' . $manifest['delete.js'];
        if (file_exists($compiledPath)) {
            echo "✓ Le fichier compilé existe\n";
        } else {
            echo "✗ Le fichier compilé n'existe pas : {$manifest['delete.js']}\n";
        }
    } else {
        echo "✗ delete.js n'est pas dans le manifest\n";
    }
} else {
    echo "✗ manifest.json n'existe pas\n";
}

// 3. Vérifier les permissions
echo "\n3. Permissions :\n";
echo "Source directory: " . substr(sprintf('%o', fileperms(__DIR__ . '/assets/js')), -4) . "\n";
echo "Target directory: " . substr(sprintf('%o', fileperms(__DIR__ . '/public/js/compiled')), -4) . "\n";

// 4. Lister tous les fichiers dans le dossier compiled
echo "\n4. Contenu du dossier compiled :\n";
$compiledFiles = glob(__DIR__ . '/public/js/compiled/*');
foreach ($compiledFiles as $file) {
    echo basename($file) . "\n";
}