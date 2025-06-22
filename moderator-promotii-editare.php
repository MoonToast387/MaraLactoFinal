<?php
require_once "includes/dbh.inc.php";

if (!isset($_GET['id'])) {
    die('Promotie invalidă!');
}
$id = (int)$_GET['id'];

// Preluare promotie
$stmt = $pdo->prepare("SELECT * FROM promotii WHERE id = ?");
$stmt->execute([$id]);
$promotie = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$promotie) {
    die('Promotia nu a fost găsită!');
}

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = trim($_POST['nume'] ?? '');
    $descriere = trim($_POST['descriere'] ?? '');
    $pret_vechi = trim($_POST['pret_vechi'] ?? '');
    $pret_nou = trim($_POST['pret_nou'] ?? '');
    if ($nume && $descriere && is_numeric($pret_vechi) && is_numeric($pret_nou)) {
        $stmt = $pdo->prepare("UPDATE promotii SET nume = ?, descriere = ?, pret_vechi = ?, pret_nou = ? WHERE id = ?");
        $stmt->execute([$nume, $descriere, $pret_vechi, $pret_nou, $id]);
        $success = true;
        // Refresh date promotie
        $stmt = $pdo->prepare("SELECT * FROM promotii WHERE id = ?");
        $stmt->execute([$id]);
        $promotie = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = 'Completează toate câmpurile corect!';
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare Promotie - Moderator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
        .container { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
<div class="container">
    <h1>Editare Promotie</h1>
    <?php if ($success): ?>
        <div class="callout success">Promoția a fost actualizată cu succes!</div>
    <?php elseif ($error): ?>
        <div class="callout alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Nume promotie:</label>
        <input type="text" name="nume" value="<?php echo htmlspecialchars($promotie['nume']); ?>" required>
        <label>Descriere:</label>
        <textarea name="descriere" required><?php echo htmlspecialchars($promotie['descriere']); ?></textarea>
        <label>Preț vechi (RON):</label>
        <input type="number" name="pret_vechi" value="<?php echo htmlspecialchars($promotie['pret_vechi']); ?>" min="0" step="0.01" required>
        <label>Preț nou (RON):</label>
        <input type="number" name="pret_nou" value="<?php echo htmlspecialchars($promotie['pret_nou']); ?>" min="0" step="0.01" required>
        <button type="submit" class="button primary" style="margin-top:15px;">Salvează modificările</button>
        <a href="moderator-promotii-vizualizare.php" class="button secondary" style="margin-top:15px;">Inapoi</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
<script>$(document).foundation();</script>
</body>
</html>
