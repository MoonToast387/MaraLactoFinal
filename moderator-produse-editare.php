<?php
require_once "includes/dbh.inc.php";

if (!isset($_GET['id'])) {
    die('Produs invalid!');
}
$id = (int)$_GET['id'];

// Preluare produs
$stmt = $pdo->prepare("SELECT * FROM produse WHERE id = ?");
$stmt->execute([$id]);
$produs = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produs) {
    die('Produsul nu a fost găsit!');
}

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = trim($_POST['nume'] ?? '');
    $descriere = trim($_POST['descriere'] ?? '');
    $pret = trim($_POST['pret'] ?? '');
    if ($nume && $descriere && is_numeric($pret)) {
        $stmt = $pdo->prepare("UPDATE produse SET nume = ?, descriere = ?, pret = ? WHERE id = ?");
        $stmt->execute([$nume, $descriere, $pret, $id]);
        $success = true;
        // Refresh date produs
        $stmt = $pdo->prepare("SELECT * FROM produse WHERE id = ?");
        $stmt->execute([$id]);
        $produs = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = 'Completează toate câmpurile corect!';
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare Produs - Moderator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
        .container { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
<div class="container">
    <h1>Editare Produs</h1>
    <?php if ($success): ?>
        <div class="callout success">Produsul a fost actualizat cu succes!</div>
    <?php elseif ($error): ?>
        <div class="callout alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Nume produs:</label>
        <input type="text" name="nume" value="<?php echo htmlspecialchars($produs['nume']); ?>" required>
        <label>Descriere:</label>
        <textarea name="descriere" required><?php echo htmlspecialchars($produs['descriere']); ?></textarea>
        <label>Preț (RON):</label>
        <input type="number" name="pret" value="<?php echo htmlspecialchars($produs['pret']); ?>" min="0" step="0.01" required>
        <button type="submit" class="button primary" style="margin-top:15px;">Salvează modificările</button>
        <a href="moderator-produse-vizualizare.php" class="button secondary" style="margin-top:15px;">Inapoi</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
<script>$(document).foundation();</script>
</body>
</html>
