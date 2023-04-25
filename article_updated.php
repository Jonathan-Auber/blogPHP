<?php
session_start();
require_once('db.php');

if (isset($_GET['id']) && $_GET['id'] > 0 && $_SESSION['role'] === "Admin") {
    $getArticle = intval($_GET['id']);
    // Récupération des articles originaux
    $reqArticle = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $reqArticle->execute([$getArticle]);
    $articleInfo = $reqArticle->fetch();
    // Récupération de la mise à jour
    $reqArticleUpdated = $pdo->prepare("SELECT * FROM articles_update WHERE article_id = ?");
    $reqArticleUpdated->execute([$getArticle]);
    $articleUpdatedInfo = $reqArticleUpdated->fetch();
    // Récupération de l'auteur
    $getAuthor = $pdo->prepare("SELECT u.id, username FROM users as u INNER JOIN articles_update as a ON u.id = a.user_id WHERE a.article_id = ?");
    $getAuthor->execute([$getArticle]);
    $author = $getAuthor->fetch();

    // Section réservée Admin
    // Si on refuse la mise à jour, on efface les données de la table tampon
    if (isset($_POST['rejectedUpdate'])) {
        $removeUpdatePicture = './upload/picture/' . $articleUpdatedInfo['Image'];
        unlink($removeUpdatePicture);
        $rejectedArticle = $pdo->prepare("DELETE FROM articles_update WHERE article_id = ?");
        $rejectedArticle->execute([$getArticle]);
        // Envoyer un mail à l'utilisateur?
        header("Location: admin.php");
    }

    // Si on valide, on récupère les données pour les transférer sur la table principale
    if (isset($_POST['validateUpdate'])) {
        $removeOldPicture = './upload/picture/' . $articleInfo['Image'];
        unlink($removeOldPicture);
        $validateArticle = $pdo->prepare("UPDATE articles SET title = ?, content = ?, image = ?, date = CURRENT_TIMESTAMP WHERE id = ?");
        $validateArticle->execute([$articleUpdatedInfo['Title'], $articleUpdatedInfo['Content'], $articleUpdatedInfo['Image'], $getArticle]);
        $deleteUpdate = $pdo->prepare("DELETE FROM articles_update WHERE article_id = ?");
        $deleteUpdate->execute([$getArticle]);
        header("Location: article.php?id=" . $getArticle);
    }

?>
    <!doctype html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Article</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    </head>

    <body>

        <?php
        include_once('header.php'); ?>
        <div class="container">
            <?php
            if (isset($_SESSION['role']) && ($_SESSION['role'] === "Admin" || $_SESSION['id'] === $articleUpdatedInfo['User_id'])) {
            ?>
                <div class="d-flex">
                    <h2 class="text-center m-3 w-50"><?= $articleInfo['Title'] ?></h2>
                    <h2 class="text-center m-3 w-50"><?= $articleUpdatedInfo['Title'] ?></h2>
                </div>
                <div class="container d-flex">
                    <div class="w-50 p-3"><img src="upload/picture/<?= $articleInfo['Image'] ?>" class="img-fluid m-3" alt="Image de l'article"></div>
                    <div class="w-50 p-3"><img src="upload/picture/<?= $articleUpdatedInfo['Image'] ?>" class="img-fluid m-3" alt="Image de l'article"></div>
                </div>
                <div class="d-flex">
                    <p class="m-4 w-50"><?= $articleInfo['Content']  ?></p>
                    <p class="m-4 w-50"><?= $articleUpdatedInfo['Content']  ?></p>
                </div>
                <h6 class="mt-5 text-end">Article rédigé par : <?= $author['username'] ?></h6>
                <?php if ($_SESSION['role'] === "Admin") { ?>
                    <form action="" method="POST">
                        <div class="text-center">
                            <button type="submit" name="rejectedUpdate" class="btn btn-danger m-5">Rejeter l'article</button>
                            <button type="submit" name="validateUpdate" class="btn btn-success m-5">Valider l'article</button>
                        </div>
                    </form>
            <?php }
            } ?>



            <?php
            if (isset($error)) {
                echo '<font color="red">' . $error . '</font>';
            }
            ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>

    </html>
<?php
} else {
    header("Location: index.php");
}
?>