<?php
session_start();
require_once 'includes/dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mesaj = trim($_POST['mesaj']);
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($userId && !empty($mesaj)) {
        try {
            $sql = "INSERT INTO mesaje (mesaj, user_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$mesaj, $userId]);
            $successMessage = "Mesaj trimis cu succes!";
        } catch (Exception $e) {
            $errorMessage = "Eroare la trimiterea mesajului: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $errorMessage = "Mesajul nu poate fi gol si trebuie să fiti autentificat.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Magazin Online - Contact</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
  </style>
</head>
<body>
  <div class="fade-in">
    <div class="top-bar">
      <div class="top-bar-left">
        <ul class="menu">
          <li class="menu-text">Mara Lacto</li>
          <?php
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
    <div class="grid-container">
      <h2>Contact</h2>
      <?php if (isset($successMessage)) { echo '<p style="color:green">' . $successMessage . '</p>'; } ?>
      <?php if (isset($errorMessage)) { echo '<p style="color:red">' . $errorMessage . '</p>'; } ?>
      <form method="POST">
        <label>Mesaj:
          <textarea name="mesaj" placeholder="Mesajul tău"></textarea>
        </label>
        <button type="submit" class="button large primary">Trimite</button>
      </form>
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
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/vendor/foundation.min.js"></script>
  <script>$(document).foundation();</script>
</body>
</html>
