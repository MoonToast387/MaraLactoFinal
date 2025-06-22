<?php
require_once "dbh.inc.php";

try {
    echo "<p></p>";

    $query = "SELECT * FROM produse";
    $stmt = $pdo->query($query);
    $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($produse)) {
        echo "<p>Nu există produse în baza de date.</p>";
    } else {
        echo "<p></p>";
        foreach ($produse as $produs) {
            echo '<div class="cell small-12 medium-6">';
            echo '<div class="callout primary">';
            echo '<h5>' . htmlspecialchars($produs['nume']) . '</h5>'; // Titlu
            echo '<p>' . htmlspecialchars($produs['descriere']) . '</p>'; // Descriere
            echo '<p>Preț: ' . htmlspecialchars($produs['pret']) . ' RON</p>'; // Pret
            echo '<img src="uploads/' . basename($produs['poza']) . '" alt="Produs" style="width: 100%; height: auto;">'; // Poza
            echo '<p>Cod produs: ' . htmlspecialchars($produs['nr_produs']) . '</p>'; // Numar produs
            echo '<div style="display: flex; gap: 10px; margin-top: 10px;">';
            echo '<form method="post" action="includes/deleteProduct.php" style="margin:0;">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($produs['id']) . '">';
            echo '<button type="submit" class="button alert">Șterge</button>';
            echo '</form>';
            echo '<a href="moderator-produse-editare.php?id=' . htmlspecialchars($produs['id']) . '" class="button warning">Editează</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
} catch (PDOException $e) {
    echo "<p>Eroare la preluarea produselor: " . $e->getMessage() . "</p>";
}
?>