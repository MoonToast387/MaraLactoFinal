<?php
require_once 'dbh.inc.php';

try {
    $query = "SELECT * FROM promotii";
    $stmt = $pdo->query($query);
    $promotii = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($promotii)) {
        echo "<p>Nu există promotii disponibile.</p>";
    } else {
        foreach ($promotii as $promotie) {
            echo '<div class="cell small-12 medium-6">';
            echo '<div class="callout primary">';
            echo '<h5>' . htmlspecialchars($promotie['nume']) . '</h5>';
            echo '<p>' . htmlspecialchars($promotie['descriere']) . '</p>';
            echo '<p>Preț Vechi: ' . htmlspecialchars($promotie['pret_vechi']) . ' RON</p>';
            echo '<p>Preț Nou: ' . htmlspecialchars($promotie['pret_nou']) . ' RON</p>';
            echo '<img src="uploads/' . basename($promotie['poza']) . '" alt="Promotie" style="width: 100%; height: auto;">';
            echo '<p>Cod produs: ' . htmlspecialchars($promotie['nr_produs']) . '</p>';
            echo '<div style="display: flex; gap: 10px; margin-top: 10px;">';
            echo '<form method="post" action="includes/deletePromotie.php" style="margin:0;">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($promotie['id']) . '">';
            echo '<button type="submit" class="button alert">Șterge</button>';
            echo '</form>';
            echo '<a href="moderator-promotii-editare.php?id=' . htmlspecialchars($promotie['id']) . '" class="button warning">Editează</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
} catch (PDOException $e) {
    echo "<p>Eroare la afișare: " . $e->getMessage() . "</p>";
}
