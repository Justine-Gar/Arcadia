<?php
function asset($path) {
    return '/public/' . ltrim($path, '/');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--====  REMIXIONS ====-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
  <!--====  CSS ====-->
  <link rel="stylesheet" href="<?= asset('css/main.css') ?>"/>
  <title><?= $title ?? 'Arcadia';?></title>
</head>
<body>
  
  <?php require_once __DIR__ . '/partials/_header.php';?>

  <main>

    <?=$content;?>

  </main>

  <?php require_once __DIR__ . '/partials/_footer.php';?>

</body>
</html>