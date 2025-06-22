<?php
require_once "dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {//verificam daca metoda este POST si daca exista un ID
    $id = (int)$_POST['id'];// convertim ID-ul la int pentru a preveni injectarea SQL pentru a nu ne suprascrie baza de date

    try {
        $query = "DELETE FROM produse WHERE id = ?";// pregatim interogarea pentru a sterge produsul cu ID-ul specificat
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);// executam interogarea cu ID-ul specificat

        header("Location: ../moderator-produse-vizualizare.php?delete=success");// redirectionam utilizatorul inapoi la pagina de vizualizare a produselor cu un mesaj de succes
        exit();
    } catch (PDOException $e) {// prindem exceptia in cazul in care apare o eroare la baza de date
        die("Eroare la ștergerea produsului: " . $e->getMessage());
    }
} else {
    header("Location: ../moderator-produse-vizualizare.php");
    exit();
}
?>