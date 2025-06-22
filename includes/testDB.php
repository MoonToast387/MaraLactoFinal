<?php
require_once "dbh.inc.php";

try {
    $query = "SELECT * FROM produse";
    $stmt = $pdo->query($query);
    $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($produse);
    echo "</pre>";
} catch (PDOException $e) {
    echo "Eroare la conectarea la baza de date: " . $e->getMessage();
}
?>