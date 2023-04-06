<?php
// On appel la base de données, ou on renvoie les erreurs s'il y en as.
try {
    $pdo = new PDO("mysql:host=localhost;dbname=blog", "root", "root");
} catch (Exception $err) {
    die(('Erreur : ' . $err->getMessage()));
}
