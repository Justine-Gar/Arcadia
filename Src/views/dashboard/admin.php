<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--====  REMIXIONS ====-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
  <!--====  CSS ====-->
  <link rel="stylesheet" href="css/main.css">
  <title><?= $title ?? 'Arcadia';?></title>
</head>
<body>

  <div>
    <div>
      <h2>dashboard</h2>
      <ul>
        <li><a href="/admin" class="active">Home</a></li>
        <li><a href="/admin/habitats">habitat</a></li>
        <li><a href="/admin/services">services</a></li>
        <li><a href="/admin/animaux">animaux</a></li>
        <li><a href="/admin/comptes">compte</a></li>
        <li><a href="/admin/staff">staff</a></li>
        <li><a href="/admin/veto">veto</a></li>
      </ul>
    </div>

    <div class="main_content">
      <h1>Tableau de Board</h1>
      <div class="dashboard_overview">

        <div class="dashboard_card">
          <h2>Animaux populaire</h2>
          <ul>
            <li>Ici sera mis les animaux les plus populaire(au click)</li>
          </ul>
        </div>

        <div class="dashboard_card">
          <h2>Compte-rendu du Jour</h2>
          <ul>
            <li>Ici se trouvera les comptre rendu du jour</li>
          </ul>
        </div>

      </div>
    </div>

  </div>
  
  
  <?php require_once __DIR__ . '/../partials/_footer.php';?>
</body>
</html>
