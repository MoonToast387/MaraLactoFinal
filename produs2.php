<?php
require_once "includes/dbh.inc.php";
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {//aici verificam daca exista parametru id si daca este numeric
    echo "<p>Produs inexistent.</p>";
    exit;
}
$id = intval($_GET['id']);
$query = "SELECT * FROM produse WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$produs = $stmt->fetch(PDO::FETCH_ASSOC);// aici preluam produsul dupa id
if (!$produs) {
    echo "<p>Produs inexistent.</p>";
    exit;
}
?>
<!DOCTYPE html> 
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($produs['nume']); ?> - Detalii Produs</title>
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
  <div class="grid-container">
    <div class="grid-x grid-padding-x align-center">
      <div class="cell small-12 medium-8 large-6">
        <div class="card" style="margin-top: 48px;">
          <img src="uploads/<?php echo htmlspecialchars($produs['poza']); ?>" alt="<?php echo htmlspecialchars($produs['nume']); ?>">
          <div class="card-section">
            <h2><?php echo htmlspecialchars($produs['nume']); ?></h2>
            <p><?php echo htmlspecialchars($produs['descriere']); ?></p>
            <p><strong>Preț:</strong> <?php echo htmlspecialchars($produs['pret']); ?> RON</p>
            <div style="display: flex; gap: 10px; justify-content: center; align-items: center; margin-top: 1.5rem;">
              <form action="add_to_cart2.php" method="get" style="display: flex; gap: 8px; align-items: center; margin: 0;">
                <input type="hidden" name="id" value="<?php echo $produs['id']; ?>">
                <input type="number" name="qty" value="1" min="1" style="width: 60px;">
                <button type="submit" class="button">Adaugă în coș</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
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
