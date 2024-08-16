<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>


<?php
require '../../../lib/config/database.php';
require '../../utils/utils.php';
require '../../utils/PasswordHasher.php';

use App\utils\PasswordHasher;
use lib\config\database;
/*
// Création de l'objet PasswordHasher
$hasher = new PasswordHasher();
// Mot de passe à hasher
$pass = '123450';
// Hashage du mot de passe
$hashedPassword = $hasher->hashPassword($pass);
// Affichage du mot de passe hashé
echo "Mot de passe hashé : " . $hashedPassword;
*/
init_php_session();

if (isset($_POST['valide_connection'])) {
    $debugData = [];  // Initialiser le tableau de débogage

    if (isset($_POST['form_email']) && !empty($_POST['form_email']) &&
        isset($_POST['form_password']) && !empty($_POST['form_password'])) {

        $email = $_POST['form_email'];
        $password = $_POST['form_password'];

        $debugData['email'] = $email;
        $debugData['passwordLength'] = strlen($password);

        try {
          $db = database::getInstance();
          $pdo = $db->getConnection();

          $sql = 'SELECT * FROM `user` WHERE `email` = :email';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':email', $email, PDO::PARAM_STR);
          $stmt->execute();

          $user = $stmt;

          if ($user) {

            $hasher = new PasswordHasher();
            if ($hasher->verifyPassword($password, $user['password'])) {
                  
              echo "Connecté";
              $_SESSION['user_id'] = $user['id'];
              $_SESSION['email'] = $user['email'];
                    
                    
                

              // Redirection après avoir collecté toutes les données de débogage
              echo "connection réussie";
               // Assurez-vous de terminer le script après la redirection
            } 
          }
        } catch (PDOException $e) {

        }

    }
}


?>

<?php if (isset($error)): ?>
    <p class='error'><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

  <!--Model de connexion-->
  <div class="modal" id="modalLogin">
    <div class="modal_container">
      <div class="model_title">
        <span class="close"><i class="ri-close-large-line"></i></span>
        <h2>Connexion</h2>
      </div>
      <form action="" method="post" id="formLogin">
        <div class="form_group">
          <label for="form_email">Email:</label>
          <input type="email" id="form_email" name="form_email" required>
        </div>
        <div class="form_group">
          <label for="form_password">Mot de passe:</label>
          <input type="password" name="form_password" id="form_password" required>
        </div>
        <button type="submit" id="btnSubmitLogin">se connecter</button>
      </form>
    </div>
  </div>
  
</body>
</html>