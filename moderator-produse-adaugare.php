<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Rapoarte Moderator - Mara Lacto</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
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
  <!-- Navigatie -->
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
    <h2>Adaugare Produs</h2>
    <form id="adaugareProdusForm" action="includes/formhandler.inc.php" method="post" enctype="multipart/form-data">
      <label>Nume:
        <input type="text" name="nume" placeholder="Numele produsului." required>
      </label>
      <label>Descriere:
        <textarea name="descriere" placeholder="Descrierea produsului detaliat." required></textarea>
      </label>
      <label>Pret:
        <input type="number" name="pret" placeholder="Pretul produsului in RON." required>
      </label>
      <label>Poza:
        <input type="file" name="poza" accept="image/*" required>
      </label>
      <label>Nr. Produs:
        <input type="text" name="nr_produs" placeholder="Produsul o sa apara in locul specificat." required>
      </label>
      <button type="submit" class="button large expanded">Adaugă!</button>
    </form>
  </div>

  <!-- Footer -->
  <footer class="grid-container">
    <hr>
    <p class="text-center">&copy; 2025 Mara Lacto. Pagina de moderator.</p>
  </footer>

  <!-- Scripturi Foundation și jQuery -->
  <script src="assets/vendor/foundation.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
  <script>
    $(document).foundation();
    // Validare formular adaugare produs
    document.getElementById('adaugareProdusForm').addEventListener('submit', function(e) {
      var nume = this.nume.value.trim();
      var descriere = this.descriere.value.trim();
      var pret = this.pret.value;
      var poza = this.poza.files[0];
      var nr_produs = this.nr_produs.value.trim();
      var errors = [];
      if (!nume) errors.push('Introduceți numele produsului.');
      if (!descriere) errors.push('Introduceți descrierea produsului.');
      if (!pret || pret <= 0) errors.push('Introduceți un preț valid (mai mare ca 0).');
      if (!poza) {
        errors.push('Selectați o imagine pentru produs.');
      } else if (!poza.type.startsWith('image/')) {
        errors.push('Fișierul selectat nu este o imagine.');
      }
      if (!nr_produs) errors.push('Introduceți numărul produsului.');
      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault();
      }
    });
  </script>
</body>
</html>
