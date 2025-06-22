<?php //formhandler pentru produse

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method: " . $_SERVER["REQUEST_METHOD"]);
}

if (!is_writable('../uploads/')) {
    die("Uploads directory is not writable.");
}

if (!isset($_FILES["poza"])) {
    die("No file uploaded.");
}

if ($_FILES["poza"]["error"] !== UPLOAD_ERR_OK) {
    die("File upload error: " . $_FILES["poza"]["error"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["poza"]) && $_FILES["poza"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($_FILES["poza"]["name"]);

        if (move_uploaded_file($_FILES["poza"]["tmp_name"], $uploadFile)) {
            $poza = $uploadFile; // Save the relative path to the database
        } else {
            die("Error moving uploaded file.");
        }
    } else {
        die("Error uploading file.");
    }

    $nume = htmlspecialchars($_POST["nume"]);
    $descriere = htmlspecialchars($_POST["descriere"]);
    $pret = (int)$_POST["pret"];
    $nr_produs = htmlspecialchars($_POST["nr_produs"]);

    try {
        require_once "dbh.inc.php";

        $query = "INSERT INTO produse (nume, descriere, pret, poza, nr_produs) VALUES (?, ?, ?, ?, ?);";
        $stmt = $pdo->prepare($query);

        $stmt->execute([$nume, $descriere, $pret, $poza, $nr_produs]);

        $pdo = null;
        $stmt = null;

        header("Location:../moderator-produse-adaugare.php");

        die();

    } catch (PDOException $e) {
        die("Bomboclad" . $e->getMessage());
    }
}else {
    header("Location: ../moderator-produse-adaugare.php");
}

//CREATE TABLE produse (
//    id INT(11) NOT NULL AUTO_INCREMENT,
//    nume VARCHAR(30) NOT NULL,
//    descriere VARCHAR(255) NOT NULL,
//    pret INT(10) NOT NULL,
//    poza VARBINARY(MAX) NOT NULL,
//    nr_produs INT(255) NOT NULL,
//    created_at DATETIME NOT NULL DEFAULT CURRENT_TIME,
//    PRIMARY KEY (id)
//    
//);