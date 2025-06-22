<?php
require_once "includes/dbh.inc.php";
session_start();
$user_type = $_SESSION['user_type'] ?? 1;
$meniu = [];
$stmt = $pdo->prepare("SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE p.meniu = 1 AND d.IdUser = ? ORDER BY p.id ASC");
$stmt->execute([$user_type]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $meniu[] = $row;
}
// Stergere blog
if (isset($_POST['delete_blog']) && isset($_POST['blog_id'])) {
    $blog_id = (int)$_POST['blog_id'];
    $stmt = $pdo->prepare("DELETE FROM bloguri WHERE id = ?");
    $stmt->execute([$blog_id]);
    header("Location: moderator-bloguri-vizualizare.php");
    exit();
}
// Ia blogurile din baza de date
$stmt = $pdo->query("SELECT * FROM bloguri ORDER BY created_at DESC");
$bloguri = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Vizualizare Bloguri - Moderator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/custom.css">
    <style>
         body { padding-top: 20px; }
        .blog-card { margin-bottom: 30px; }
        .blog-img { max-width: 100%; height: auto; margin-bottom: 10px; }
        .blog-title { font-size: 1.5em; font-weight: bold; }
        .blog-date { color: #888; font-size: 0.95em; }
        .container { max-width: 900px; margin: 0 auto; }
        .delete-btn { float: right; margin-left: 10px; }
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
    <h1 style="text-align:center;">Bloguri</h1>
    <?php if (empty($bloguri)): ?>
        <div class="callout warning">Nu există bloguri în baza de date.</div>
    <?php else: ?>
        <?php foreach ($bloguri as $blog): ?>
            <div class="card blog-card">
                <div class="card-section">
                    <div class="blog-title"><?php echo htmlspecialchars($blog['titlu']); ?></div>
                    <div class="blog-date">Publicat la: <?php echo htmlspecialchars($blog['created_at']); ?></div>
                    <?php if ($blog['poza']): ?>
                        <img src="<?php echo htmlspecialchars($blog['poza']); ?>" class="blog-img" alt="Poza blog">
                    <?php endif; ?>
                    <div><strong>Text 1:</strong><br><?php echo nl2br(htmlspecialchars($blog['text1'])); ?></div>
                    <div style="margin-top:10px;"><strong>Text 2:</strong><br><?php echo nl2br(htmlspecialchars($blog['text2'])); ?></div>
                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <form method="post" style="display:inline;" onsubmit="return confirm('Sigur vrei să ștergi acest blog?');">
                            <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                            <button type="submit" name="delete_blog" class="button alert small">Șterge</button>
                        </form>
                        <a href="moderator-bloguri-editare.php?id=<?php echo htmlspecialchars($blog['id']); ?>" class="button warning small">Editează</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<footer class="grid-container">
    <hr>
    <p class="text-center">&copy; 2025 Mara Lacto. Pagina de moderator.</p>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
<script>$(document).foundation();</script>
</body>
</html>
