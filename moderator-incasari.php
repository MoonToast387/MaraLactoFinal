<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Incasări - Mara Lacto</title>
  <!-- Include Foundation CSS prin CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
  <!-- Stiluri custom -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
    /* Stiluri suplimentare pentru pagina de comenzi/încasări */
    body { padding-top: 20px; }
    body {
      background-color: #f7f7f7;
    }
    .card {
      margin-bottom: 20px;
    }
    .card-divider {
      font-size: 1.2em;
    }
    .order-info p {
      margin: 4px 0;
    }
    .order-email {
      font-style: italic;
      color: #555;
    }
    .order-cost {
      background-color: #d4edda; /* verde deschis */
      color: #155724;
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: bold;
      display: inline-block;
    }
    .grid-container {
      margin-top: 20px;
    }
    footer {
      margin-top: 40px;
    }
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

// Fetch incasari with user details
$incasariStmt = $pdo->prepare(
    "SELECT i.adresa, i.cost, i.created_at, u.nume, u.email, u.nr_telefon
     FROM incasari i
     INNER JOIN users u ON i.user_id = u.id
     ORDER BY i.created_at DESC"
);
$incasariStmt->execute();
$incasari = $incasariStmt->fetchAll(PDO::FETCH_ASSOC);
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

  <!-- Conținut Incasări / Comenzi -->
  <div class="grid-container">
    <h1>Încasări</h1>
    <p>Aici poți vizualiza numele utilizatorilor, detaliile utilizatorilor și costul comenzii.</p>
    <?php foreach ($incasari as $inc): ?>
      <div class="card">
        <div class="card-divider">
          <?php echo htmlspecialchars($inc['nume']); ?>
        </div>
        <div class="card-section order-info">
          <p class="order-email"><?php echo htmlspecialchars($inc['email']); ?></p>
          <p class="order-email"><?php echo htmlspecialchars($inc['nr_telefon']); ?></p>
          <p>Data comenzii: <?php echo htmlspecialchars(date('Y-m-d', strtotime($inc['created_at']))); ?></p>
          <p>Cost: <span class="order-cost"><?php echo htmlspecialchars($inc['cost']); ?> RON</span></p>
        </div>
      </div>
    <?php endforeach; ?>
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
