<?php //includem fisierul acesta pe pagninile unde avem nevoie de conexiunea la baza de date

$dsn = "mysql:host=localhost;dbname=maralacto"; // Numele bazei de date
$dbusername = "root"; // Numele de utilizator al bazei de date
$dbpassword = ""; // Parola bazei de date

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword); // Cream o noua instanta PDO care este folosita pentru interogari sql peste tot in program.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Setam modul de raportare a erorilor
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();//Prindem exceptia si afisam mesajul de eroare
    
}