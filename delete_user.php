<?php
session_start();
require_once('db.php');


if (isset($_GET['id']) && $_GET['id'] > 0) {
    $getId = intval($_GET['id']);
    if (isset($_SESSION) && $_SESSION['role'] === "Admin" || $getId === $_SESSION['id']) {
        // SUPPRESSION D'UTILISATEUR
        if (isset($_POST['deleteUser'])) {
            $accountDeleteId = 1;
            // On change l'id des articles
            $updateAllUserArticle = $pdo->prepare("UPDATE articles SET user_id = ? WHERE user_id = ?");
            $updateAllUserArticle->execute([$accountDeleteId, $getId]);

            // On change l'id des commentaires
            $updateAllUserComment = $pdo->prepare("UPDATE comments SET user_id = ? WHERE user_id = ?");
            $updateAllUserComment->execute([$accountDeleteId, $getId]);

            // On supprime l'utilisateur
            $deleteUser = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $deleteUser->execute([$getId]);

            // On supprime les articles non validés qui appartiennent au compte supprimé
            $deleteInvalidArticle = $pdo->prepare("DELETE FROM articles WHERE user_id = ? AND statute != 'Validate'");
            $deleteInvalidArticle->execute([$accountDeleteId]);

            header("Location: admin.php");
        } else {
            header("index.php");
        }
    } else {
        header("logout.php");
    }
} else {
    header("index.php");
}
