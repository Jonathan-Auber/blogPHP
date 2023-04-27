<?php
session_start();
require_once('db.php');

if (isset($_SESSION['id'], $_GET['id']) && $_GET['id'] > 0) {
    $articleId = intval($_GET['id']);
    $isLikeExist = $pdo->prepare("SELECT * FROM like_counter WHERE user_id = ? AND article_id = ?");
    $isLikeExist->execute([$_SESSION['id'], $articleId]);
    $count = $isLikeExist->rowCount();
    if ($count === 1) {
        $deleteLike = $pdo->prepare("DELETE FROM like_counter WHERE user_id = ? AND article_id = ?");
        $deleteLike->execute([$_SESSION['id'], $articleId]);
        header("Location: index.php");
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
