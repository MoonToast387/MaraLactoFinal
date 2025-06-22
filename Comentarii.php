<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Comentarii Semnalate - Mara Lacto</title>
  <!-- Foundation CSS CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
    /* Stiluri suplimentare pentru pagina de comentarii */
    body { padding-top: 20px; }
    .comment-card {
      margin-bottom: 10px;
    }
    .comment-header {
      font-weight: bold;
      margin-bottom: 2px;
    }
    .comment-body {
      margin-left: 15px;
    }
  </style>
</head>
<body>
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

  // Fetch comments and user details
  $stmt = $pdo->prepare(
      "SELECT m.mesaj, m.created_at, u.nume, u.email, u.nr_telefon, m.id 
       FROM mesaje m 
       INNER JOIN users u ON m.user_id = u.id 
       ORDER BY m.created_at DESC"
  );
  $stmt->execute();
  $comentarii = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (isset($_POST['delete_comment'])) {
      $commentId = $_POST['comment_id'];
      try {
          $delete = $pdo->prepare("DELETE FROM mesaje WHERE id = ?");
          $delete->execute([$commentId]);
          header("Location: Comentarii.php");
          exit();
      } catch (Exception $e) {
          error_log('Failed to delete comment: ' . $e->getMessage());
          exit('Eroare: Nu s-a putut șterge comentariul.');
      }
  }
  ?>
  <!-- Navigație -->
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

  <!-- Conținut Pagina Comentarii Semnalate -->
  <div class="grid-container">
    <h1>Comentarii Semnalate</h1>
    <p>Aici poți vizualiza numele utilizatorilor și comentariile semnalate.</p>

    <?php foreach ($comentarii as $comentariu): ?>
      <div class="card comment-card">
        <div class="card-divider comment-header">
           <strong><?php echo htmlspecialchars($comentariu['nume']); ?></strong>
           <span style="margin-left: 10px; font-size: 0.9em; color: gray;">
             | Email: <?php echo htmlspecialchars($comentariu['email']); ?>
           </span>
           <span style="margin-left: 10px; font-size: 0.9em; color: gray;">
             | Telefon: <?php echo htmlspecialchars($comentariu['nr_telefon']); ?>
           </span>
           <span style="float: right; font-size: 0.9em; color: gray;">
             | <?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($comentariu['created_at']))); ?>
           </span>
        </div>
        <div class="card-section comment-body">
          <p style="word-wrap: break-word; pre-wrap;">
            <?php echo htmlspecialchars($comentariu['mesaj']); ?>
          </p>
          <form method="post" action="" style="text-align:right;">
              <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comentariu['id']); ?>">
              <button type="submit" name="delete_comment" class="button alert small" onclick="return confirm('Sigur vrei să ștergi acest comentariu?');">Șterge</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Footer -->
  <footer class="grid-container">
    <hr>
    <p class="text-center">&copy; 2025 Mara Lacto. Pagina de moderator.</p>
  </footer>

  <!-- Scripturi Foundation și jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>
