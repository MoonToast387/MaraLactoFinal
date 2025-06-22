<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'dbh.inc.php';

    $id = $_POST['id'];

    try {
        $query = "DELETE FROM promotii WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);

        header('Location: ../moderator-promotii-vizualizare.php?delete=success');
        exit();
    } catch (PDOException $e) {
        echo "Eroare la ștergere: " . $e->getMessage();
    }
} else {
    header('Location: ../moderator-promotii-vizualizare.php');
    exit();
}
