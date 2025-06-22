<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Termeni și Condiții - Mara Lacto</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="assets/vendor/foundation.min.css">
  <link rel="stylesheet" href="assets/css/custom.css">
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
  </div>

  <!-- Conținut termeni -->
  <div class="grid-container">
    <h2>Termeni și Condiții</h2>
    <p><h1></h1>
        <p>Ultima actualizare: Martie 2025</p>
        
        <h4>1. Introducere</h4>
        <p>Prin accesarea și utilizarea magazinului nostru de brânzeturi, sunteți de acord cu termenii și condițiile de mai jos.</p>
        
        <h4>2. Produse și Comenzi</h4>
        <p>Produsele noastre sunt prezentate cu detalii precise, dar pot exista variații minore. Comenzile sunt procesate în ordinea primirii și confirmate prin e-mail.</p>
        
        <h4>3. Plată și Facturare</h4>
        <p>Acceptăm plăți online și ramburs. Toate prețurile includ TVA, iar factura este emisă electronic.</p>
        
        <h4>4. Livrare</h4>
        <p>Livrarea se face în termen de 2-5 zile lucrătoare. Costurile și metodele de livrare sunt specificate în pagina de checkout.</p>
        
        <h4>5. Politica de Returnare</h4>
        <p>Produsele alimentare nu pot fi returnate, cu excepția cazurilor în care acestea sunt defecte sau neconforme. Reclamațiile se depun în termen de 24 de ore de la primirea produsului.</p>
        
        <h4>6. Protecția Datelor</h4>
        <p>Respectăm confidențialitatea datelor dumneavoastră. Informațiile colectate sunt utilizate doar pentru procesarea comenzilor.</p>
        
        <h4>7. Modificări ale Termenilor</h4>
        <p>Ne rezervăm dreptul de a modifica termenii și condițiile, fără notificare prealabilă.</p>
        
        <h4>8. Contact</h4>
        <p>Pentru întrebări, ne puteți contacta la <a href="mailto:Mara Lacto">MaraLacto@gmail.com</a>.</p></p>
  </div>

  <script src="assets/vendor/foundation.min.js"></script>
  <script>
    $(document).foundation();
  </script>
</body>
</html>
