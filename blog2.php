<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Magazin Online - Blog</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
  </style>
</head>
<body>
  <div class="fade-in">
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
    
    <div class="blog-container">
      <h2>Blog</h2>
      <?php
      // Afișare bloguri din baza de date
      $stmt = $pdo->query("SELECT * FROM bloguri ORDER BY created_at DESC");
      $bloguri = $stmt->fetchAll(PDO::FETCH_ASSOC);
      if (empty($bloguri)) {
        echo '<div class="callout warning">Nu există articole de blog disponibile.</div>';
      } else {
        foreach ($bloguri as $blog) {
          echo '<div class="card">';
          echo '<div class="card-divider">';
          echo '<h4>' . htmlspecialchars($blog['titlu']) . '</h4>';
          echo '</div>';
          if ($blog['poza']) {
            echo '<img src="' . htmlspecialchars($blog['poza']) . '" alt="Poza blog">';
          }
          echo '<div class="card-section">';
          echo '<p>' . nl2br(htmlspecialchars($blog['text1'])) . '</p>';
          if ($blog['text2']) {
            echo '<hr><p>' . nl2br(htmlspecialchars($blog['text2'])) . '</p>';
          }
          echo '<div style="color:#888; font-size:0.9em; margin-top:10px;">Publicat la: ' . htmlspecialchars($blog['created_at']) . '</div>';
          echo '</div>';
          echo '</div>';
        }
      }
      ?>
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
  </div>
  <style>
    .blog-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }
    .card {
      width: 300px;
      margin: 20px 0;
      text-align: center;
    }
    .card img {
      max-width: 100%;
      height: auto;
    }
    h2 {
      text-align: center;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/vendor/foundation.min.js"></script>
  <script>$(document).foundation();</script>
</body>
</html>
