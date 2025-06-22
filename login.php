<?php
require_once "includes/dbh.inc.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sterge orice produs ramas in cos la login
    unset($_SESSION['cart2']);

    $email = trim($_POST["email"] ?? "");//
    $parola = $_POST["parola"] ?? "";
    
    if ($email === "" || $parola === "") {
        $eroare = "Toate câmpurile sunt obligatorii.";
    } else {
        $stmt = $pdo->prepare("SELECT id, email, parola, type FROM users WHERE email = ? LIMIT 1");// interogare pentru a verifica daca emailul exista
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);//aici se face interogarea pentru a obtine utilizatorul
        if ($user && $parola === $user["parola"]) {// verificam daca parola introdusa este corecta
            $_SESSION["user_id"] = $user["id"];// setam ID-ul utilizatorului in sesiune
            $_SESSION["user_email"] = $user["email"];// setam emailul utilizatorului in sesiune
            $_SESSION["user_type"] = $user["type"];// setam tipul utilizatorului in sesiune
            if ($user["type"] == 1) {// verificam daca utilizatorul este administrator
                header("Location: ModMain.php");// redirectionam utilizatorul catre pagina de administrator sau user mai jos
                exit;
            } else {
                header("Location: index2.php");
                exit;
            }
        } else {
            $eroare = "Email sau parolă incorecte.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
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
        <li><a href="index.html">Acasă</a></li>
        <li><a href="produse.php">Produse</a></li>
        <li><a href="promotii.php">Promoții</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="blog.html">Bloguri</a></li>
      </ul>
    </div>
  </div>
    <div class="grid-container">
        <h2>Autentificare</h2>
        <?php if (!empty($eroare)): ?>
          <div class="callout alert"><?php echo $eroare; ?></div>
        <?php endif; ?>
        <form method="post" action="login.php">
          <label>Email:
            <input type="email" name="email" placeholder="you@example.com" required>
          </label>
          <label>Parola:
            <input type="password" name="parola" placeholder="******" required>
          </label>
          <button class="button expanded" type="submit">Log In</button>
        </form>
        <p>Nu ai cont? <a href="inregistrare.php">Înregistrează-te</a></p>
      </div>
</body>
</html>