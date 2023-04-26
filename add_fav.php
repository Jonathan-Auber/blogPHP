<?php
session_start();
require_once('db.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $articleId = intval($_GET['id']);
    $isFavExist = $pdo->prepare("SELECT * FROM favorite WHERE user_id = ? AND article_id = ?");
    $isFavExist->execute([$_SESSION['id'], $articleId]);
    $count = $isFavExist->rowCount();
    if($count == 0) {
        $insertFavorite = $pdo->prepare("INSERT INTO favorite (user_id, article_id) VALUES (?, ?)");
        $insertFavorite->execute([$_SESSION['id'], $articleId]);
        header("Location: index.php");
    } else {
        header("Location: index.php");
    }
}
