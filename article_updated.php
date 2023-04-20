<?php
session_start();
require_once('db.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $getArticle = intval($_GET['id']);
    $reqArticle = $pdo->prepare("SELECT * FROM articles_update WHERE article_id = ?");
    $reqArticle->execute([$getArticle]);
    $articleInfo = $reqArticle->fetch();
    $getAuthor = $pdo->prepare("SELECT u.id, username FROM users as u INNER JOIN articles_update as a ON u.id = a.user_id WHERE a.article_id = ?");
    $getAuthor->execute([$getArticle]);
    $author = $getAuthor->fetch();

    // Section réservée Admin
    // Delete ?
    if (isset($_POST['rejected'])) {
        $rejectedArticle = $pdo->prepare("DELETE FROM articles_update WHERE id = ?");
        $rejectedArticle->execute([$getArticle]);
        header("Location: admin.php");
    }

    // Récupérer sur la base tampon et envoyé sur la base article
    if (isset($_POST['validate'])) {
        $validateArticle = $pdo->prepare("UPDATE articles SET date = CURRENT_TIMESTAMP, statute = 'Validate', reporting = '' WHERE id = ?");
        $validateArticle->execute([$getArticle]);
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
            if (isset($_SESSION['role']) && ($_SESSION['role'] === "Admin" || $_SESSION['id'] === $articleInfo['User_id'])) {
            ?>
                <h2 class="text-center m-5"><?= $articleInfo['Title'] ?></h2>
                <div class="container d-flex justify-content-center"><img src="upload/picture/<?= $articleInfo['Image'] ?>" class="img-fluid m-5" alt="Image de l'article"></div>
                <p class=""><?= $articleInfo['Content']  ?></p>
                <h6 class="mt-5 text-end">Article rédigé par : <?= $author['username'] ?></h6>
                <?php if ($_SESSION['role'] === "Admin") { ?>
                    <form action="" method="POST">
                        <div class="text-center">
                            <button type="submit" name="rejected" class="btn btn-danger m-5">Rejeter l'article</button>
                            <button type="submit" name="validate" class="btn btn-success m-5">Valider l'article</button>
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
}
?>