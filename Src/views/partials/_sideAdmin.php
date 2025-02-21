<div class="dash_sidebar">
  <h2>dashboard</h2>
  <ul>
    <li><a href="/admin" <?php echo ($_SERVER['REQUEST_URI'] == '/admin') ? 'class="active"' : ''; ?>>Home</a></li>
    <li><a href="/admin/habitats" <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/habitats') !== false) ? 'class="active"' : ''; ?>>habitats</a></li>
    <li><a href="/admin/services" <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/services') !== false) ? 'class="active"' : ''; ?>>services</a></li>
    <li><a href="/admin/animaux" <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/animaux') !== false) ? 'class="active"' : ''; ?>>animaux</a></li>
    <li><a href="/admin/journal" <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/journal') !== false) ? 'class="active"' : ''; ?>>journal</a></li>
    <li><a href="/admin/comptes" <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/comptes') !== false) ? 'class="active"' : ''; ?>>comptes</a></li>
    <li><a href="/admin/mails" <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/mails') !== false) ? 'class="active"' : ''; ?>>boite mail</a></li>
  </ul>
</div>