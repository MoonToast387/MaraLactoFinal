<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Magazin Online - Despre Noi</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
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

  <!-- Conținut despre -->
  <div class="grid-container">
    <h2>Despre Noi</h2>
    <p>Suntem un magazin de Lactate din Mara Muuu, Misiunea noastra e sa aveti lactate fresh zilnic de cea mai mare calitate!*</p>
    <img src="Img/Stock people1.jpg" alt="Stock people1">
    <p>*In limita stockului disponibil.</p>
  </div>

  <footer class="grid-container full">
    <div class="grid-x grid-padding-x align-center">
      <div class="cell small-12 text-center">
        <hr>
        <ul class="menu align-center">
          <li><a href="termeni-si-conditii2.php">Termeni și Condiții</a></li>
          <li><a href="despre2.php">About us</a></li>
        </ul>
        <p>&copy; 2025 Mara Lacto. Toate drepturile rezervate.</p>
      </div>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/vendor/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>
