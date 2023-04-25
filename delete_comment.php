<?php
session_start();
require("db.php");

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $commentId = intval($_GET['id']);
    $getCommentInfo = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
    $getCommentInfo->execute([$commentId]);
    $commentInfo = $getCommentInfo->fetch(PDO::FETCH_ASSOC);
    var_dump($commentInfo);
    if (isset($_SESSION) && $_SESSION['role'] === "Admin" || $_SESSION['id'] === $commentInfo['User_id']) {
        $deleteComment = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $deleteComment->execute([$commentInfo['Id']]);
        header("Location: article.php?id=" . $commentInfo['Article_id']);
    } else {
        header('Location: logout');
    }
} else {
    header('Location: index.php');
}
