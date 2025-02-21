<?php
// Placer ce fichier à la racine de votre projet
echo "Vérification de la configuration...\n\n";

// Vérifier le dossier assets/js
echo "1. Vérification des fichiers sources :\n";
$sourceFiles = ['avis.js', 'login.js', 'logout.js', 'modify.js', 'add.js', 'delete.js'];
$sourcePath = __DIR__ . '/assets/js/';
foreach ($sourceFiles as $file) {
    if (file_exists($sourcePath . $file)) {
        echo "✓ {$file} trouvé\n";
    } else {
        echo "✗ {$file} manquant\n";
    }
}

// Vérifier le dossier public/js
echo "\n2. Vérification des fichiers publics :\n";
$publicFiles = ['burger.js', 'homes.js', 'habitat.js'];
$publicPath = __DIR__ . '/public/js/';
foreach ($publicFiles as $file) {
    if (file_exists($publicPath . $file)) {
        echo "✓ {$file} trouvé\n";
    } else {
        echo "✗ {$file} manquant\n";
    }
}

// Vérifier le dossier compiled
echo "\n3. Vérification du dossier compiled :\n";
$compiledPath = __DIR__ . '/public/js/compiled/';
if (is_dir($compiledPath)) {
    echo "✓ Dossier compiled existe\n";
    $manifest = $compiledPath . 'manifest.json';
    if (file_exists($manifest)) {
        echo "✓ manifest.json trouvé\n";
        $content = json_decode(file_get_contents($manifest), true);
        if ($content) {
            echo "✓ manifest.json valide\n";
            echo "Contenu du manifest :\n";
            print_r($content);
        } else {
            echo "✗ manifest.json invalide\n";
        }
    } else {
        echo "✗ manifest.json manquant\n";
    }
} else {
    echo "✗ Dossier compiled manquant\n";
}