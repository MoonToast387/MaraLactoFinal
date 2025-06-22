<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Magazin Online - Contul Meu</title>
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

  <!-- Conținut cont -->
  <div class="grid-container">
    <h2>Contul Meu</h2>
    <p>Sunteti inregistrat.</p>
    <p>Detalii despre cont:</p>
    <p>____________________________________</p>
    <?php
    if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];

      // Salvează nume nou
      if (isset($_POST['save_nume']) && !empty($_POST['nume_nou'])) {
        $numeNou = trim($_POST['nume_nou']);
        $update = $pdo->prepare("UPDATE users SET nume = ? WHERE id = ?");
        $update->execute([$numeNou, $userId]);
        // Refresh page to show updated name
        header("Location: contul-meu2.php");
        exit();
      }
      // Salvează parola nouă
      if (isset($_POST['save_parola']) && !empty($_POST['parola_noua'])) {
        $parolaNoua = $_POST['parola_noua'];
        $update = $pdo->prepare("UPDATE users SET parola = ? WHERE id = ?");
        $update->execute([$parolaNoua, $userId]);
        header("Location: contul-meu2.php");
        exit();
      }
      $stmt = $pdo->prepare("SELECT nume, email, nr_telefon FROM users WHERE id = ?");
      $stmt->execute([$userId]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($user) {
        echo '<form method="post" action="" style="margin-bottom: 0;">';
        // Nume utilizator editabil
        if (isset($_POST['edit_nume'])) {
          echo '<input type="text" name="nume_nou" value="' . htmlspecialchars($user['nume']) . '" required> ';
          echo '<button type="submit" name="save_nume" class="button small">Salvează</button>';
        } else {
          echo '<p><strong>Nume utilizator: </strong>' . htmlspecialchars($user['nume']) . ' <button type="submit" name="edit_nume" class="button small">Editează</button></p>';
        }
        echo '</form>';
        // Parola editabilă
        echo '<form method="post" action="" style="margin-bottom: 0;">';
        if (isset($_POST['edit_parola'])) {
          echo '<input type="password" name="parola_noua" placeholder="Parola nouă" required> ';
          echo '<button type="submit" name="save_parola" class="button small">Salvează</button>';
        } else {
          echo '<p><strong>Parola: </strong> ******** <button type="submit" name="edit_parola" class="button small">Editează</button></p>';
        }
        echo '</form>';
        echo '<p><strong>Email: </strong>' . htmlspecialchars($user['email']) . '</p>';
        echo '<p><strong>Nr. Telefon: </strong>' . htmlspecialchars($user['nr_telefon']) . '</p>';
      } else {
        echo '<p>Datele contului nu au putut fi găsite.</p>';
      }
    } else {
      echo '<p>Nu sunteți autentificat.</p>';
    }
    ?>
    <p>_____________________________________</p>
  </div>

  <form method="post" action="" style="text-align:center;">
    <button type="submit" name="delete_account" class="button alert large" onclick="return confirm('Sigur vrei să ștergi acest cont? Această acțiune este ireversibilă!');">Șterge contul</button>
  </form>
  <p></p>
  <img src="Img/Fall.gif">

  <script src="assets/vendor/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>
<?php
if (isset($_POST['delete_account'])) {
    if (!isset($_SESSION['user_id'])) {
        error_log('User ID not found in session.');
        exit('Eroare: Nu sunteți autentificat.');
    }

    $userId = $_SESSION['user_id'];
    try {
        $delete = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $delete->execute([$userId]);
        session_destroy();
        header("Location: index.html");
        exit();
    } catch (Exception $e) {
        error_log('Failed to delete account: ' . $e->getMessage());
        exit('Eroare: Nu s-a putut șterge contul.');
    }
}
?>
