<?php
require_once "includes/dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nume = trim($_POST["nume"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $parola = $_POST["parola"] ?? "";
    $nr_telefon = trim($_POST["nr_telefon"] ?? "");
    $eroare = "";
    if ($nume === "" || $email === "" || $parola === "" || $nr_telefon === "") {
        $eroare = "Toate câmpurile sunt obligatorii.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $eroare = "Emailul există deja. <a href='login.php'>Conectează-te aici</a> sau folosește alt email.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (nume, email, parola, nr_telefon, type) VALUES (?, ?, ?, ?, 2)");
            $stmt->execute([
                $nume,
                $email,
                $parola,
                $nr_telefon
            ]);
            header("Location: login.php?inregistrat=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare</title>
    <link rel="stylesheet" href="style.css">
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
      <div class="grid-x grid-padding-x align-center">
        <div class="cell small-12 medium-8 large-6 main-faded-bg" style="margin-top: 2rem; margin-bottom: 2rem;">
          <h2 class="text-center">Înregistrare</h2>
          <?php if (!empty($eroare)): ?>
            <div class="callout alert text-center"><?php echo $eroare; ?></div>
          <?php endif; ?>
          <form action="inregistrare.php" method="post">
            <label for="nume">Nume:
              <input type="text" id="nume" name="nume" required>
            </label>
            <label for="email">Email:
              <input type="email" id="email" name="email" required>
            </label>
            <label for="parola">Parolă:
              <input type="password" id="parola" name="parola" required>
            </label>
            <label for="nr_telefon">Număr de telefon:
              <input type="text" id="nr_telefon" name="nr_telefon" required>
            </label>
            <button type="submit" class="button expanded">Înregistrează-te</button>
          </form>
          <p class="text-center" style="margin-top: 1rem;">Ai deja un cont? <a href="login.php">Conectează-te aici</a>.</p>
        </div>
      </div>
    </div>
<script>
document.querySelector('form[action="inregistrare.php"]').addEventListener('submit', function(e) {
  let nume = document.getElementById('nume').value.trim();
  let email = document.getElementById('email').value.trim();
  let parola = document.getElementById('parola').value;
  let telefon = document.getElementById('nr_telefon').value.trim();
  let eroare = '';

  if (nume.length < 2) {
    eroare += 'Numele trebuie să aibă cel puțin 2 caractere.\n';
  }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    eroare += 'Emailul nu este valid.\n';
  }
  if (parola.length < 6) {
    eroare += 'Parola trebuie să aibă cel puțin 6 caractere.\n';
  }
  const phoneRegex = /^\d{8,}$/;
  if (!phoneRegex.test(telefon)) {
    eroare += 'Numărul de telefon trebuie să conțină doar cifre și să aibă cel puțin 8 cifre.\n';
  }
  if (eroare) {
    alert(eroare);
    e.preventDefault();
  }
});
</script>
</body>
</html>