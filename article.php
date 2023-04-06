<?php
session_start();
require_once('db.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $getArticle = intval($_GET['id']);
    $reqArticle = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $reqArticle->execute([$getArticle]);
    $articleInfo = $reqArticle->fetch();
    $getAuthor = $pdo->prepare("SELECT username FROM users as u INNER JOIN articles as a ON u.id = a.user_id WHERE a.id = ?");
    $getAuthor->execute([$getArticle]);
    $author = $getAuthor->fetch();

?>
    <!doctype html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Signup</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    </head>

    <body>
    <?php 
    include_once('header.php');
    ?>
        <div class="container">
            <h2 class="text-center m-5"><?= $articleInfo['Title'] ?></h2>

            <p><?= $articleInfo['Content']  ?></p>
            <h6>Article rédigé par : <?= $author['username'] ?></h6>

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