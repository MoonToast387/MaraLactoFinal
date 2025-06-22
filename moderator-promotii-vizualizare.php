<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Vizualizare Promotii - Mara Lacto</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/custom.css"> 
  <style>
    body { padding-top: 20px; }
  </style>
</head>
<body>
  <!-- Navigatie -->
  <div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li class="menu-text">Mara Lacto - Moderator</li>
        <?php
        require_once "includes/dbh.inc.php";
        session_start();
        $user_type = $_SESSION['user_type'] ?? 1; // default admin
        $meniu = [];
        $stmt = $pdo->prepare("SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE p.meniu = 1 AND d.IdUser = ? ORDER BY p.id ASC");
        $stmt->execute([$user_type]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {//aici se face interogarea pentru a obtine meniul
            $meniu[] = $row;// se adauga in array-ul $meniu
        }
        foreach ($meniu as $item): ?>
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

  <!-- Continut Rapoarte -->
  <div class="grid-container">
    <h1>Promotii Moderator</h1>
    <p>Aici găsești toate informațiile despre promotii.</p>
    
    <!-- Search Bar -->
    <div class="grid-container">
      <form method="get" action="moderator-promotii-vizualizare.php">
        <label for="search">Caută promotii:</label>
        <input type="text" id="search" name="search" placeholder="Introdu numele promotiei...">
        <button type="submit" class="button">Cauta</button>
      </form>
    </div>

    <!-- Rezultate cautare -->
    <div class="grid-x grid-padding-x">
      <?php
      if (isset($_GET['search']) && !empty($_GET['search'])) {
          $search = htmlspecialchars($_GET['search']);
          require_once "includes/dbh.inc.php";

          try {
              $query = "SELECT * FROM promotii WHERE nume LIKE ?";
              $stmt = $pdo->prepare($query);
              $stmt->execute(["%$search%"]);
              $promotii = $stmt->fetchAll(PDO::FETCH_ASSOC);

              if (empty($promotii)) {
                  echo "<p>Nu s-au găsit promotii pentru căutarea: <strong>$search</strong>.</p>";
              } else {
                  foreach ($promotii as $promotie) {
                      echo '<div class="cell small-12 medium-6">';
                      echo '<div class="callout primary">';
                      echo '<h5>' . htmlspecialchars($promotie['nume']) . '</h5>';
                      echo '<p>' . htmlspecialchars($promotie['descriere']) . '</p>';
                      echo '<p>Preț Vechi: ' . htmlspecialchars($promotie['pret_vechi']) . ' RON</p>';
                      echo '<p>Preț Nou: ' . htmlspecialchars($promotie['pret_nou']) . ' RON</p>';
                      echo '<img src="uploads/' . basename($promotie['poza']) . '" alt="Promotie" style="width: 100%; height: auto;">';
                      echo '<p>Cod produs: ' . htmlspecialchars($promotie['nr_produs']) . '</p>';
                      echo '<div style="display: flex; gap: 10px; margin-top: 10px;">';
                      echo '<form method="post" action="includes/deletePromotie.php" style="margin:0;">';
                      echo '<input type="hidden" name="id" value="' . htmlspecialchars($promotie['id']) . '">';
                      echo '<button type="submit" class="button alert">Șterge</button>';
                      echo '</form>';
                      echo '<a href="moderator-promotii-editare.php?id=' . htmlspecialchars($promotie['id']) . '" class="button warning">Editează</a>';
                      echo '</div>';
                      echo '</div>';
                      echo '</div>';
                  }
              }
          } catch (PDOException $e) {
              echo "<p>Eroare la căutare: " . $e->getMessage() . "</p>";
          }
      } else {
          ini_set('display_errors', 1);
          ini_set('display_startup_errors', 1);
          error_reporting(E_ALL);
          include "includes/getPromotii.php";
      }
      ?>
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
