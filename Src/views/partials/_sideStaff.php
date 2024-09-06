<div class="dash_sidebar">
  <h2>dashboard</h2>
  <ul>
    <li><a href="/staff" <?php echo ($_SERVER['REQUEST_URI'] == '/staff') ? 'class="active"' : ''; ?>>Home</a></li>
    <li><a href="/staff/review" <?php echo (strpos($_SERVER['REQUEST_URI'], '/staff/review') !== false) ? 'class="active"' : ''; ?>>avis</a></li>
    <li><a href="/staff/journal" <?php echo (strpos($_SERVER['REQUEST_URI'], '/staff/journal') !== false) ? 'class="active"' : ''; ?>>journal</a></li>
  </ul>
</div>