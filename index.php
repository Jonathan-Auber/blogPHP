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
    <?php
    include_once('header.php');
    ?>
    <div class="container">
        <h2 class="text-center m-5">BIENVENUE</h2>
        <?php
        $displayArticle = $pdo->prepare("SELECT * FROM articles ORDER BY id DESC");
        $displayArticle->execute();
        $articles = $displayArticle->fetchAll();

        ?> <div class="container d-flex flex-wrap justify-content-center">
            <?php foreach ($articles as $article) {
                // Affichage des articles validé seulement
                // if ($article["Statute"] === "Validate") {
                $searchAuthor = $pdo->prepare("SELECT username FROM users as u INNER JOIN articles as a ON u.id = ?");
                $searchAuthor->execute([$article["User_id"]]);
                $searchAuthor->fetch();
                $author = $searchAuthor->fetch();
                $isFavExist = $pdo->prepare("SELECT * FROM favorite WHERE user_id = ? AND article_id = ?");
                $isFavExist->execute([$_SESSION['id'], $article['Id']]);
                $countFav = $isFavExist->rowCount();
                $isLikeExist = $pdo->prepare("SELECT * FROM like_counter WHERE user_id = ? AND article_id = ?");
                $isLikeExist->execute([$_SESSION['id'], $article['Id']]);
                $countLike = $isLikeExist->rowCount();

            ?>

                <div class="card m-5" style="width: 20rem;">
                    <img src="upload/picture/<?= $article['Image'] ?>" class="card-img-top" alt="Image de présentation de l'article">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><?= $article['Title'] ?></h5>
                            <p class="card-text overflow-hidden" style="height: 25vh;"><?= $article['Content'] ?></p>
                            <p class="author">Article rédigé par : <span class="fw-bold"><?= $author['username']; ?></span></p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="article.php?id=<?= $article['Id'] ?>" class="btn btn-primary">Lire l'article</a>
                            <span>
                                <?php 
                                if ($countLike === 0) { ?>
                                    <a class="mx-3" href="like.php?id=<?= $article['Id'] ?>"><i class="fa-regular fa-thumbs-up"></i></a>
                                <?php } elseif ($countLike === 1) { ?>
                                    <a class="mx-3" href="unlike.php?id=<?= $article['Id'] ?>"><i class="fa-solid fa-thumbs-up"></i></a>
                                <?php } if ($countFav === 0) { ?>
                                    <a href="add_fav.php?id=<?= $article['Id'] ?>"><i class="text-danger fa-regular fa-heart"></i></a>
                                <?php } elseif ($countFav === 1) { ?>
                                    <a href="remove_fav.php?id=<?= $article['Id'] ?>"><i class="text-danger fa-solid fa-heart"></i></a>
                                <?php  } ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php
                // }
            }
            ?>
        </div>




    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>