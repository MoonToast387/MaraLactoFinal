<?php
require_once "includes/dbh.inc.php";
session_start();
$user_type = $_SESSION['user_type'] ?? 1; // default admin
$meniu = [];
$stmt = $pdo->prepare("SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE p.meniu = 1 AND d.IdUser = ? ORDER BY p.id ASC");
$stmt->execute([$user_type]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $meniu[] = $row;
}

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titlu = trim($_POST['titlu'] ?? '');
    $text1 = trim($_POST['text1'] ?? '');
    $text2 = trim($_POST['text2'] ?? '');
    $poza = '';

    // Upload imagine daca exista
    if (isset($_FILES['poza']) && $_FILES['poza']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['poza']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $poza = 'uploads/' . uniqid('blog_', true) . '.' . $ext;
            move_uploaded_file($_FILES['poza']['tmp_name'], $poza);
        } else {
            $error = 'Format poză invalid!';
        }
    }

    if (!$error && $titlu && $text1 && $text2) {
        $stmt = $pdo->prepare("INSERT INTO bloguri (titlu, text1, poza, text2, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$titlu, $text1, $poza, $text2]);
        $success = true;
    } elseif (!$error) {
        $error = 'Completează toate câmpurile!';
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adăugare Bloguri - Moderator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
    body { padding-top: 20px; }
        .add-btn { width: 100%; font-size: 1.5em; margin-top: 20px; }
        .form-label { font-weight: bold; }
        .container { max-width: 700px; margin: 0 auto; }
    </style>
</head>
<body>
<!-- Meniu dinamic -->
<div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li class="menu-text">Mara Lacto - Moderator</li>
        <?php foreach ($meniu as $item): ?>
          <li><a href="<?php echo htmlspecialchars($item['pagina']); ?>"><?php echo htmlspecialchars($item['nume_meniu']); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="top-bar-right">
      <ul class="menu">
        <li><a href="index.html" class="button">Log Out</a></li>
      </ul>
    </div>
</div>
<div class="container">
    <h1 style="text-align:center;">Adaugare Bloguri</h1>
    <?php if ($success): ?>
        <div class="callout success">Blog adăugat cu succes!</div>
    <?php elseif ($error): ?>
        <div class="callout alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label class="form-label">Titlu:</label>
        <input type="text" name="titlu" required>
        <label class="form-label">Text 1:</label>
        <textarea name="text1" rows="4" required></textarea>
        <label class="form-label">Poză:</label>
        <input type="file" name="poza" accept="image/*" required>
        <label class="form-label">Text 2:</label>
        <textarea name="text2" rows="4" required></textarea>
        <button type="submit" class="button primary add-btn">Adaugă!</button>
    </form>
</div>
<!-- Footer -->
<footer class="grid-container">
    <hr>
    <p class="text-center">&copy; 2025 Mara Lacto. Pagina de moderator.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
<script>$(document).foundation();</script>
</body>
</html>
