<?php
require_once "includes/dbh.inc.php";

if (!isset($_GET['id'])) {
    die('Blog invalid!');
}
$id = (int)$_GET['id'];

// Preluare blog
$stmt = $pdo->prepare("SELECT * FROM bloguri WHERE id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$blog) {
    die('Blogul nu a fost găsit!');
}

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titlu = trim($_POST['titlu'] ?? '');
    $text1 = trim($_POST['text1'] ?? '');
    $text2 = trim($_POST['text2'] ?? '');
    if ($titlu && $text1 && $text2) {
        $stmt = $pdo->prepare("UPDATE bloguri SET titlu = ?, text1 = ?, text2 = ? WHERE id = ?");
        $stmt->execute([$titlu, $text1, $text2, $id]);
        $success = true;
        // Refresh date blog
        $stmt = $pdo->prepare("SELECT * FROM bloguri WHERE id = ?");
        $stmt->execute([$id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = 'Completează toate câmpurile!';
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare Blog - Moderator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
        .container { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
<div class="container">
    <h1>Editare Blog</h1>
    <?php if ($success): ?>
        <div class="callout success">Blogul a fost actualizat cu succes!</div>
    <?php elseif ($error): ?>
        <div class="callout alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Titlu:</label>
        <input type="text" name="titlu" value="<?php echo htmlspecialchars($blog['titlu']); ?>" required>
        <label>Text 1:</label>
        <textarea name="text1" rows="4" required><?php echo htmlspecialchars($blog['text1']); ?></textarea>
        <label>Text 2:</label>
        <textarea name="text2" rows="4" required><?php echo htmlspecialchars($blog['text2']); ?></textarea>
        <button type="submit" class="button primary" style="margin-top:15px;">Salvează modificările</button>
        <a href="moderator-bloguri-vizualizare.php" class="button secondary" style="margin-top:15px;">Inapoi</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
<script>$(document).foundation();</script>
</body>
</html>
