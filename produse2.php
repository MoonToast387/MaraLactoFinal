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
  <!-- Navigație -->
  <div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li class="menu-text">Mara Lacto</li>
        <?php
        session_start();
        require_once 'includes/dbh.inc.php';
        $userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 2;//aici luam tipul de utilizator din sesiune
        try {
          $sql = "SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE d.IdUser = ? AND p.meniu = 1 ORDER BY p.id ASC";
          $stmt = $pdo->prepare($sql);//pregatim interogarea
          $stmt->execute([$userType]);//executam interogarea
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

  <div class="callout success" data-closable="slide-right">
    <h4>Produse</h4>
    <p></p>
  </div>
  
  <div class="grid-container">
    <h2></h2>
    <form method="get" action="produse2.php">
      <label>Cautare produse
        <input type="text" name="search" placeholder="Cautare dupa nume">
      </label>
      <button type="submit" class="button">Cauta</button>
    </form>
  </div>

  <!-- Conținut Produse -->
  <div class="grid-container">
    <h2>Lista de Produse</h2>
    <div class="grid-x grid-padding-x small-up-2 medium-up-3 large-up-4">
      <?php
      require_once "includes/dbh.inc.php";

      if (isset($_GET['search']) && !empty($_GET['search'])) {// verificam daca exista parametru de cautare
          $search = htmlspecialchars($_GET['search']);
          $query = "SELECT * FROM produse WHERE nume LIKE ?";// interogarea pentru cautare
          $stmt = $pdo->prepare($query);
          $stmt->execute(["%$search%"]);
          $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {// daca nu exista parametru de cautare, luam toate produsele
          $query = "SELECT * FROM produse";// interogarea pentru toate produsele
          $stmt = $pdo->query($query);// executam interogarea
          $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);// preluam toate produsele
      }

      try {
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
                  echo '<a href="produs2.php?id=' . htmlspecialchars($produs['id']) . '" class="button small">Detalii</a>'; //aici redirectam la pruds2.php
                  echo '</div>';
                  echo '</div>';
                  echo '</div>';
              }
          }
      } catch (PDOException $e) {
          echo "<p>Eroare la preluarea produselor: " . $e->getMessage() . "</p>";
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
