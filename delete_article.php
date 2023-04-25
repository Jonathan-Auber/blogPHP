<?php
// PAGE EN COURS
session_start();
require_once('db.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $getArticle = intval($_GET['id']);
    $reqArticle = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $reqArticle->execute([$getArticle]);
    $articleInfo = $reqArticle->fetch();    
    // $getAuthor = $pdo->prepare("SELECT u.id, username FROM users as u INNER JOIN articles as a ON u.id = a.user_id WHERE a.id = ?");
    // $getAuthor->execute([$getArticle]);
    // $author = $getAuthor->fetch();

    if (isset($_SESSION) && $_SESSION['role'] === "Admin" || $_SESSION['id'] === $author['id']) {
        if (isset($_POST['deleteArticle'])) {
            $pictureName = $articleInfo['Image'];
            $deletePicture = './upload/picture/' . $pictureName;
            unlink($deletePicture);
            $deleteArticle = $pdo->prepare("DELETE FROM articles WHERE id = ?");
            $deleteArticle->execute([$getArticle]);
            if ($_SESSION['role'] === "Admin") {
                header("Location: admin.php");
            } elseif ($_SESSION['role'] === "User") {
                header("Location: profil.php?id=" . $_SESSION['id']);
            }
        } else {
            header("index.php");
        }
    } else {
        header("logout.php");
    }
} else {
    header("index.php");
}
