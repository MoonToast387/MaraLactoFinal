<?php
session_start();
// Goleste cosul dupa confirmare
unset($_SESSION['cart2']);
header('Location: comanda-confirmata.php');
exit;
