<div class="dash_sidebar">
  <h2>dashboard</h2>
  <ul>
    <li><a href="/veto" <?php echo ($_SERVER['REQUEST_URI'] == '/veto') ? 'class="active"' : ''; ?>>Home</a></li>
    <li><a href="/veto/journal" <?php echo (strpos($_SERVER['REQUEST_URI'], '/veto/journal') !== false) ? 'class="active"' : ''; ?>>journal</a></li>
  </ul>
</div>