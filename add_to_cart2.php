<?php
session_start();
require_once "includes/dbh.inc.php";
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: produse2.php');
    exit;
}
$id = intval($_GET['id']);
$qty = (isset($_GET['qty']) && is_numeric($_GET['qty']) && $_GET['qty'] > 0) ? intval($_GET['qty']) : 1;
$query = "SELECT * FROM produse WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$produs = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produs) {
    header('Location: produse2.php');
    exit;
}
if (!isset($_SESSION['cart2'])) {
    $_SESSION['cart2'] = [];
}
if (isset($_SESSION['cart2'][$id])) {
    $_SESSION['cart2'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart2'][$id] = [
        'nume' => $produs['nume'],
        'pret' => $produs['pret'],
        'poza' => $produs['poza'],
        'qty' => $qty
    ];
}
header('Location: produse2.php');
exit;
