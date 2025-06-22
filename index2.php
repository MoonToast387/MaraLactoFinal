<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Magazin Online - Pagina Principală</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <!-- CSS pentru animație -->
  <style>
    /* Clasa aplicată containerului pentru animație */
    .fade-in {
      animation: fadeInAnimation 2s ease forwards; /* animație de 2 secunde */
      opacity: 0;
    }

    @keyframes fadeInAnimation {
      0% {
        opacity: 0;
        transform: translateY(-20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    body {
      background-image: url('Img/Background2.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center center;
      min-height: 100vh;
    }

    .main-faded-bg {
      background: rgba(255,255,255,0.85);
      border-radius: 24px;
      box-shadow: 0 8px 32px 0 rgba(31,38,135,0.15);
      padding: 2.5rem 2rem 2rem 2rem;
      margin-top: 2rem;
      margin-bottom: 0;
    }

    @media (max-width: 600px) {
      .main-faded-bg { padding: 1rem; }
    }

    .footer-faded-bg {
      background: rgba(255,255,255,0.85);
      border-radius: 16px;
      box-shadow: 0 4px 16px 0 rgba(31,38,135,0.10);
      padding: 1.5rem 1rem 1rem 1rem;
      margin-bottom: 1.5rem;
      display: inline-block;
    }

    .footer-note {
      display: inline-block;
      margin-top: 0.5rem;
      padding: 0.3em 1em;
      background: rgba(184, 224, 243, 0.85);
      border-radius: 8px;
      font-size: 1rem;
    }
  </style>
</head>
<body>

  <!-- Containerul principal cu animația aplicată -->
  <div class="fade-in">
    <!-- Navigație principală -->
    <div class="top-bar">
      <div class="top-bar-left">
        <ul class="menu">
          <li class="menu-text">Mara Lacto</li>
          <?php
          session_start();
          require_once 'includes/dbh.inc.php';
          $userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 2; // 2 = user by default
          try {
            $sql = "SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE d.IdUser = ? AND p.meniu = 1 ORDER BY p.id ASC";
            $stmt = $pdo->prepare($sql);// pregatim interogarea
            $stmt->execute([$userType]);// executam interogarea 
            $found = false;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {// preluam fiecare rand
              echo '<li><a href="' . htmlspecialchars($row['pagina']) . '">' . htmlspecialchars($row['nume_meniu']) . '</a></li>';// afisam meniul
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

    <!-- Conținut principal -->
    <div class="grid-container">
      <div class="grid-x grid-padding-x align-center">
        <div class="cell small-12 medium-8 large-6 main-faded-bg">
          <h1 class="text-center" style="color: rgb(55, 107, 218);">Bun venit la magazinul nostru online!</h1>
          <p class="lead text-center">Descoperă cele mai noi produse la prețuri avantajoase.</p>
          <p style="color: rgb(12, 223, 57);" class="lead text-center">Logged in</p>
          <div class="text-center">
            <img src="Img/Logo.png" alt="Logo Mara Lacto">
            <a href="produse2.php" class="button large expanded">Vezi Produse</a>
          </div>
        </div>
      </div>
      <div class="grid-x grid-padding-x align-center">
        <div class="cell small-12 medium-8 large-6">
          <div class="footer-faded-bg" style="margin-top: 0.5rem;">
            <hr>
            <ul class="menu align-center">
              <li><a href="termeni-si-conditii2.php">Termeni și Condiții</a></li>
              <li><a href="despre2.php">About us</a></li>
            </ul>
            <p>&copy; 2025 Mara Lacto. Toate drepturile rezervate.</p>
            <span class="footer-note">Autentificare necesara pentru checkout.</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripturi Foundation și jQuery (asigură-te că jQuery este inclus înainte de Foundation!) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/vendor/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>

