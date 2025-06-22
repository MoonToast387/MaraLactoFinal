<?php
session_start();
require_once 'includes/dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresa = trim($_POST['adresa']);
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $total = 0;

    if (!empty($_SESSION['cart2'])) {
        foreach ($_SESSION['cart2'] as $produs) {
            $total += $produs['qty'] * $produs['pret'];
        }
    }

    if ($userId && !empty($adresa) && $total > 0) {
        try {
            $sql = "INSERT INTO incasari (user_id, adresa, cost, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $adresa, $total]);
            $comandaSalvata = true;
            unset($_SESSION['cart2']); // Sterge produsele din cos
            header("Location: comanda-confirmata.php"); // Redirect la pagina de confirmare
            exit();
        } catch (Exception $e) {
            $eroareSalvare = 'Eroare la salvarea comenzii: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        $eroareDate = 'Datele sunt incomplete sau coșul este gol.';
    }
}

$total = 0;
if (!empty($_SESSION['cart2'])) {
  foreach ($_SESSION['cart2'] as $produs) {
    $total += $produs['qty'] * $produs['pret'];
  }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Mara Lacto</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
  <div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li class="menu-text">Mara Lacto</li>
        <?php
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
  <div class="grid-container">
    <h2>Checkout</h2>
    <?php if ($total > 0): ?>
      <div class="callout success">
        <h4>Total de plată: <?php echo $total; ?> RON</h4>
        <p>Vă mulțumim pentru comandă! Pentru finalizare, vă rugăm să confirmați datele de livrare și să apăsați pe butonul de confirmare.</p>
        <p>Produsele se vor achita fizic la adresa de livrare cu card bancar sau cash.</p>
        <form method="POST" action="">
          <label>Adresă de livrare:
            <input type="text" name="adresa" required>
          </label>
          <button type="submit" class="button success">Confirmă Comanda</button>
        </form>
        <?php if (isset($comandaSalvata) && $comandaSalvata): ?>
          <p style="color:green">Comanda confirmată și salvată cu succes!</p>
        <?php elseif (isset($eroareSalvare)): ?>
          <p style="color:red"><?php echo $eroareSalvare; ?></p>
        <?php elseif (isset($eroareDate)): ?>
          <p style="color:orange"><?php echo $eroareDate; ?></p>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <div class="callout alert">
        <p>Coșul este gol. Adaugă produse pentru a continua la checkout.</p>
      </div>
    <?php endif; ?>
  </div>
  <footer class="grid-container full">
    <div class="grid-x grid-padding-x align-center">
      <div class="cell small-12 text-center">
        <hr>
        <ul class="menu align-center">
          <li><a href="termeni-si-conditii2.html">Termeni și Condiții</a></li>
          <li><a href="despre2.html">About us</a></li>
        </ul>
        <p>&copy; 2025 Mara Lacto. Toate drepturile rezervate.</p>
      </div>
    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/vendor/foundation.min.js"></script>
  <script>$(document).foundation();</script>
</body>
</html>
