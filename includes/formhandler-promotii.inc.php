<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'dbh.inc.php';

    $nume = $_POST['nume'];
    $descriere = $_POST['descriere'];
    $pret_vechi = $_POST['pret_vechi'];
    $pret_nou = $_POST['pret_nou'];
    $nr_produs = $_POST['nr_produs'];

    // Handle file upload
    if (isset($_FILES['poza']) && $_FILES['poza']['error'] === UPLOAD_ERR_OK) {
        $pozaTmpName = $_FILES['poza']['tmp_name'];
        $pozaName = basename($_FILES['poza']['name']);
        $pozaPath = '../uploads/' . $pozaName;

        if (move_uploaded_file($pozaTmpName, $pozaPath)) {
            try {
                $query = "INSERT INTO promotii (nume, descriere, pret_vechi, pret_nou, poza, nr_produs) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$nume, $descriere, $pret_vechi, $pret_nou, $pozaName, $nr_produs]);

                header('Location: ../moderator-promotii-adaugare.php?success=1');
                exit();
            } catch (PDOException $e) {
                echo "Eroare la adăugare: " . $e->getMessage();
            }
        } else {
            echo "Eroare la încărcarea fișierului.";
        }
    } else {
        echo "Eroare: Fișierul nu a fost încărcat corect.";
    }
} else {
    header('Location: ../moderator-promotii-adaugare.php');
    exit();
}
