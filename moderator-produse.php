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
    <h1>Produse Moderator</h1>
    <p>Aici găsești toate informațiile depre produse.</p>
    
    <div class="grid-x grid-padding-x">
      <!-- Raport comentarii semnalate -->
      <div class="cell small-12 medium-6">
        <div class="callout primary">
          <h5>Produse</h5>
          <p>Vizualizează produsele de pe site!</p>
          <a href="moderator-produse-vizualizare.php" class="button small">Vizualizează</a>
        </div>
      </div>
      <!-- Raport utilizatori blocați -->
      <div class="cell small-12 medium-6">
        <div class="callout success">
          <h5>Adaugare Produse</h5>
          <p>Adauga produse noi pentru magazinul online.</p>
          <a href="moderator-produse-adaugare.php" class="button small">Adauga</a>
        </div>
      </div>
      <!-- Raport promotii vizualizare -->
      <div class="cell small-12 medium-6">
        <div class="callout primary">
          <h5>Promotii</h5>
          <p>Vizualizează promotiile de pe site!</p>
          <a href="moderator-promotii-vizualizare.php" class="button small">Vizualizează</a>
        </div>
      </div>
      <!-- Raport promotii adaugare -->
      <div class="cell small-12 medium-6">
        <div class="callout success">
          <h5>Adaugare Promotii</h5>
          <p>Adauga promotii noi pentru magazinul online.</p>
          <a href="moderator-promotii-adaugare.php" class="button small">Adauga</a>
        </div>
      </div>
      <!-- Raport promotii vizualizare -->
      <div class="cell small-12 medium-6">
        <div class="callout primary">
          <h5>Bloguri</h5>
          <p>Vizualizează Blogurile de pe site!</p>
          <a href="moderator-bloguri-vizualizare.php" class="button small">Vizualizează</a>
        </div>
      </div>
      <!-- Raport promotii adaugare -->
      <div class="cell small-12 medium-6">
        <div class="callout success">
          <h5>Adaugare Bloguri</h5>
          <p>Adauga bloguri noi pentru magazinul online.</p>
          <a href="moderator-bloguri-adaugare.php" class="button small">Adauga</a>
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
