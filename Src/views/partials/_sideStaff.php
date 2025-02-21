<div class="dash_sidebar">
  <h2>dashboard</h2>
  <ul>
    <li><a href="/staff" <?php echo ($_SERVER['REQUEST_URI'] == '/staff') ? 'class="active"' : ''; ?>>Home</a></li>
    <li><a href="/staff/avis" <?php echo (strpos($_SERVER['REQUEST_URI'], '/staff/avis') !== false) ? 'class="active"' : ''; ?>>avis</a></li>
    <li><a href="/staff/services" <?php echo (strpos($_SERVER['REQUEST_URI'], '/staff/services') !== false) ? 'class="active"' : ''; ?>>service</a></li>
    <li><a href="/staff/journal" <?php echo (strpos($_SERVER['REQUEST_URI'], '/staff/journal') !== false) ? 'class="active"' : ''; ?>>journal</a></li>
  </ul>
</div>