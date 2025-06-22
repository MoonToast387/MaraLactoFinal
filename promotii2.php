<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Magazin Online - Promoții</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
    .card img {
      width: 260px;
      height: 260px;
      object-fit: cover;
      margin: 0 auto;
      display: block;
    }
    .card {
      min-height: 420px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .card-section {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
    }
  </style>
</head>
<body>
  <!-- Navigație -->
  <div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li class="menu-text">Mara Lacto</li>
        <?php
        session_start();
        require_once 'includes/dbh.inc.php';
        $userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 2;
        try {
          $sql = "SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE d.IdUser = ? AND p.meniu = 1 ORDER BY p.id ASC";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$userType]);
          $found = false;
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<li><a href="' . htmlspecialchars($row['pagina']) . '">' . htmlspecialchars($row['nume_meniu']) . '</a></li>';
            $found = true;
          }
          if (!$found) echo '<li><span style="color:orange">Niciun meniu găsit</span></li>';
        } catch (Exception $e) {
          echo '<li><span style="color:red">Eroare meniu: ' . htmlspecialchars($e->getMessage()) . '</span></li>';
        }
        ?>
      </ul>
    </div>
    <div class="top-bar-right">
      <ul class="menu">
        <li><a href="index.html" class="button">Log out</a></li>
      </ul> 
    </div>
  </div>

  <!-- Conținut Promoții -->
  <div class="grid-container">
    <h2>Lista de Promoții</h2>
    <div class="grid-x grid-padding-x small-up-2 medium-up-3 large-up-4">
      <?php
      require_once "includes/dbh.inc.php";

      $query = "SELECT * FROM promotii";
      $stmt = $pdo->query($query);
      $promotii = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (empty($promotii)) {
          echo "<p>Nu există promoții în baza de date.</p>";
      } else {
          foreach ($promotii as $promotie) {
              echo '<div class="cell">';
              echo '<div class="card">';
              echo '<img src="uploads/' . htmlspecialchars($promotie['poza']) . '" alt="' . htmlspecialchars($promotie['nume']) . '">';
              echo '<div class="card-section">';
              echo '<h4>' . htmlspecialchars($promotie['nume']) . '</h4>';
              echo '<p>' . htmlspecialchars($promotie['descriere']) . '</p>';
              echo '<p>Preț Vechi: ' . htmlspecialchars($promotie['pret_vechi']) . ' RON</p>';
              echo '<p>Preț Nou: ' . htmlspecialchars($promotie['pret_nou']) . ' RON</p>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
          }
      }
      ?>
    </div>
  </div>

  <!-- Scripturi Foundation -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/what-input/5.2.10/what-input.min.js"></script>
  <script src="assets/vendor/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>
