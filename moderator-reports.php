<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Rapoarte Moderator - Mara Lacto</title>
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

  // Get total users
  $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
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

  <!-- Conținut Rapoarte -->
  <div class="grid-container">
    <h1>Rapoarte Moderator</h1>
    <p>Aici găsești rapoarte privind activitatea utilizatorilor și comentariile semnalate.</p>
    
    <div class="grid-x grid-padding-x">
      <!-- Raport comentarii semnalate -->
      <div class="cell small-12 medium-6">
        <div class="callout warning">
          <h5>Comentarii Semnalate</h5>
          <p>Număr total: 3</p>
          <a href="Comentarii.php" class="button small">Vizualizează</a>
        </div>
      </div>
      <!-- Raport utilizatori blocați -->
      <div class="cell small-12 medium-6">
        <div class="callout alert">
          <h5>Utilizatori Blocați</h5>
          <p>Număr total: 1</p>
          <a href="#" class="button small">Vizualizează</a>
        </div>
      </div>
      <div class="cell small-12 medium-6">
        <div class="callout success">
          <h5>Încasări</h5>
          <p>Comenzi finalizate</p>
          <a href="moderator-incasari.php" class="button small">Vizualizează</a>
        </div>
      </div>
      <div class="cell small-12 medium-6">
        <div class="callout primary">
          <h5>Detalii utilizatori</h5>
          <p>Număr total: <?php echo $totalUsers; ?></p>
          <a href="moderator-utilizatori.php" class="button small">Vizualizează</a>
        </div>
      </div>
    </div>
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
