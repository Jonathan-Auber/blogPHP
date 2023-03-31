<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=blog", "root", "root");
} catch (Exception $err) {
    die(('Erreur : ' . $err->getMessage()));
}
