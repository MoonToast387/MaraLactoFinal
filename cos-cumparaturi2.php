<?php
session_start();
require_once "includes/dbh.inc.php";
// Ștergere produs din coș
if (isset($_POST['delete_cart_item']) && isset($_POST['produs_id'])) {
    $id = $_POST['produs_id'];
    if (isset($_SESSION['cart2'][$id])) {
        unset($_SESSION['cart2'][$id]);
    }
    header('Location: cos-cumparaturi2.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Coș de cumpărături - Mara Lacto</title>
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
    <h2>Coșul tău de cumpărături</h2>
    <?php
    $total = 0;
    if (!empty($_SESSION['cart2'])) {
      echo '<table class="unstriped"><thead><tr><th>Produs</th><th>Imagine</th><th>Cantitate</th><th>Preț unitar</th><th>Total</th><th></th></tr></thead><tbody>';
      foreach ($_SESSION['cart2'] as $id => $produs) {
        $subtotal = $produs['qty'] * $produs['pret'];
        $total += $subtotal;
        echo '<tr>';
        echo '<td>' . htmlspecialchars($produs['nume']) . '</td>';
        echo '<td><img src="uploads/' . htmlspecialchars($produs['poza']) . '" alt="' . htmlspecialchars($produs['nume']) . '" style="max-width:60px;"></td>';
        echo '<td>' . $produs['qty'] . '</td>';
        echo '<td>' . htmlspecialchars($produs['pret']) . ' RON</td>';
        echo '<td>' . $subtotal . ' RON</td>';
        echo '<td>';
        echo '<form method="post" style="margin:0;">';
        echo '<input type="hidden" name="produs_id" value="' . htmlspecialchars($id) . '">';
        echo '<button type="submit" name="delete_cart_item" class="button alert small" onclick="return confirm(\'Sigur vrei să ștergi acest produs din coș?\');">Șterge</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
      }
      echo '</tbody></table>';
      echo '<h4 class="text-right">Total de plată: ' . $total . ' RON</h4>';
      echo '<a href="checkout.php" class="button success">Checkout</a>';
    } else {
      echo '<p>Coșul este gol.</p>';
    }
    ?>
    
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
  <script>$(document).foundation();</script>
</body>
</html>
