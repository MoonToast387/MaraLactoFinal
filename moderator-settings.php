<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Setări Moderator - Mara Lacto</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
    body { padding-top: 20px; }
  </style>
</head>
<body>
  <?php
  require_once "includes/dbh.inc.php";
  session_start();
  $user_type = $_SESSION['user_type'] ?? 1; // default admin
  $meniu = [];
  $stmt = $pdo->prepare("SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE p.meniu = 1 AND d.IdUser = ? ORDER BY p.id ASC");
  $stmt->execute([$user_type]);
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $meniu[] = $row;
  }
  ?>
  <!-- Navigație -->
  <div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li class="menu-text">Mara Lacto - Moderator</li>
        <?php foreach ($meniu as $item): ?>
          <li><a href="<?php echo htmlspecialchars($item['pagina']); ?>"><?php echo htmlspecialchars($item['nume_meniu']); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="top-bar-right">
      <ul class="menu">
        <li><a href="index.html" class="button">Log Out</a></li>
      </ul>
    </div>
  </div>

  <!-- Conținut Setări -->
  <div class="grid-container">
    <h1>Setări Moderator</h1>
    <p>Aici poți modifica setările legate de permisiuni, notificări și alte opțiuni administrative.</p>
    
    <form>
      <label>Setează mesaj de întâmpinare:
        <input type="text" placeholder="Mesaj personalizat..." required>
      </label>
      <label>Notificări email:
        <select>
          <option>Activ</option>
          <option>Inactiv</option>
        </select>
      </label>
      <button type="submit" class="button">Salvează Setările</button>
    </form>
  </div>

  <!-- Footer -->
  <footer class="grid-container">
    <hr>
    <p class="text-center">&copy; 2025 Mara Lacto. Pagina de moderator.</p>
  </footer>

  <!-- Scripturi Foundation și jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>
