<?php
session_start();
require_once("db.php");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/7710422967.js" crossorigin="anonymous"></script>
</head>

<body>
    <a href="logout.php">Déco</a>
    <div class="container">
        <h2 class="text-center m-5">BIENVENUE</h2>
        <?php
        $displayArticle = $pdo->prepare("SELECT * FROM articles ORDER BY id DESC");
        $displayArticle->execute();
        $articles = $displayArticle->fetchAll();

        ?> <div class="container d-flex flex-wrap">
            <?php foreach ($articles as $article) {
                $searchAuthor = $pdo->prepare("SELECT username FROM users as u INNER JOIN articles as a ON u.id = ? ");
                $searchAuthor->execute([$article["User_id"]]);
                $searchAuthor->fetch();
                $author = $searchAuthor->fetch();
            ?>

                <div class="card m-5" style="width: 20rem;">
                    <img src="assets/img/Dune_img.jpeg" class="card-img-top" alt="Image de présentation de l'article">
                    <div class="card-body">
                        <h5 class="card-title"><?= $article['Title'] ?></h5>
                        <p class="card-text"><?= $article['Content'] ?></p>
                        <p class="author">Article rédigé par : <span class="fw-bold"><?= $author['username']; ?></span></p>
                        <div class="d-flex justify-content-between align-items-center"><a href="article.php?id=<?= $article['Id'] ?>" class="btn btn-primary">Lire l'article</a> <span><a href="#" class="mx-3"><i class="fa-regular fa-heart"></i></a><a href="#"><i class="fa-regular fa-thumbs-up"></i></a></span></div>
                    </div>
                </div>
            <?php  }  ?>
        </div>




    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>