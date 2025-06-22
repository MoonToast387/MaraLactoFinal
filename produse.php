<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Magazin Online - Produse</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
    .card img {
      width: 260px;
      height: 260px;
      object-fit: cover;
      margin: 0 auto;
      display: block;
    }
    .card {
      min-height: 420px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .card-section {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
    }
  </style>
</head>
<body>
  <!-- Navigatie -->
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
    <div class="top-bar-right">
      <ul class="menu">
        <li><a href="login.php" class="button">Log In</a></li>
        <li><a href="contul-meu.html" class="button">Contul Meu</a></li>
        <li><a href="cos-cumparaturi.html" class="button">Cos</a></li>
      </ul>
    </div>
  </div>

  

  <div class="callout success" data-closable="slide-right">
    <h5>Inregistrativa pentru a incepe cumparaturile.</h5>
    <a href="login.html">login</a>
    <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="grid-container">
    <h2></h2>
    <form method="get" action="produse.php">
      <label>Cautare produse
        <input type="text" name="search" placeholder="Cautare dupa nume">
      </label>
      <button type="submit" class="button">Cauta</button>
    </form>
  </div>
  

  <!-- Continut Produse -->
  <div class="grid-container">
    <h2>Lista de Produse</h2>
    <div class="grid-x grid-padding-x small-up-2 medium-up-3 large-up-4">
      <?php
      require_once "includes/dbh.inc.php";

      if (isset($_GET['search']) && !empty($_GET['search'])) {
          $search = htmlspecialchars($_GET['search']);
          $query = "SELECT * FROM produse WHERE nume LIKE ?";
          $stmt = $pdo->prepare($query);
          $stmt->execute(["%$search%"]);
          $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
          $query = "SELECT * FROM produse";
          $stmt = $pdo->query($query);
          $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      if (empty($produse)) {
          echo "<p>Nu există produse în baza de date.</p>";
      } else {
          foreach ($produse as $produs) {
              echo '<div class="cell">';
              echo '<div class="card">';
              echo '<img src="uploads/' . htmlspecialchars($produs['poza']) . '" alt="' . htmlspecialchars($produs['nume']) . '">';
              echo '<div class="card-section">';
              echo '<h4>' . htmlspecialchars($produs['nume']) . '</h4>';
              echo '<p>' . htmlspecialchars($produs['descriere']) . '</p>';
              echo '<p>Preț: ' . htmlspecialchars($produs['pret']) . ' RON</p>';
              echo '<a href="#" class="button small" disabled>Detalii</a>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
          }
      }
      ?>
    </div>
  </div>

  <!-- Scripturi Foundation -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/what-input/5.2.10/what-input.min.js"></script>
  <script src="assets/vendor/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>
