<?php
/**
 * Helper pour gérer les chemins des fichiers JS
 */
function getJS($filename) {
    // Liste des fichiers JS publics
    $publicFiles = ['burger.js', 'homes.js', 'habitat.js'];
    
    // Si c'est un fichier public, retourner le chemin direct
    if (in_array($filename, $publicFiles)) {
        return "/js/{$filename}";
    }
    
    // Sinon chercher dans le manifest
    $manifestFile = __DIR__ . '/../public/js/compiled/manifest.json';
    if (file_exists($manifestFile)) {
        $manifest = json_decode(file_get_contents($manifestFile), true);
        if (isset($manifest[$filename])) {
            return "/js/compiled/{$manifest[$filename]}";
        }
    }
    
    // Fallback au chemin original si quelque chose ne va pas
    return "/js/{$filename}";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--====  REMIXIONS ====-->
  <!--====  CSS ====-->
  <link rel="stylesheet" href="/css/main.css">
  <title><?= $title ?? 'Arcadia';?></title>
</head>
<body>
  
  <?php require_once __DIR__ . '/partials/_header.php';?>

  <main>

    <?php 
    echo $content;
    ?>

  </main>

  <?php require_once __DIR__ . '/partials/_footer.php';?>

  <script src="/js/burger.js"></script>
  <script src="/js/homes.js"></script>
  <script src="/js/habitat.js"></script>
  
  <!-- Scripts protégés - version temporaire pour debug -->
  <script src="/js/avis.js"></script>
  <script src="/js/login.js"></script>
  <script src="/js/logout.js"></script>
  <script src="/js/modify.js"></script>
  <script src="/js/add.js"></script>
  <script src="/js/delete.js"></script>
  <script src="/js/time.js"></script>
  <script src="/js/contact.js"></script>
  <script src="/js/upload.js"></script>
  <script src="/js/review.js"></script>

  <script>
    console.log('Page chargée');
    document.querySelector('button[data-type="modify"]')?.addEventListener('click', () => {
        console.log('Click test');
    });
  </script>
  
</body>
</html>