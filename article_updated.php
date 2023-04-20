<?php
session_start();
require_once('db.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $getArticle = intval($_GET['id']);
    $reqArticle = $pdo->prepare("SELECT * FROM articles_update WHERE id = ?");
    $reqArticle->execute([$getArticle]);
    $articleInfo = $reqArticle->fetch();
    $getAuthor = $pdo->prepare("SELECT u.id, username FROM users as u INNER JOIN articles_update as a ON u.id = a.user_id WHERE a.id = ?");
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
            if ($articleInfo['Statute'] === "Validate") { ?>
                <h2 class="text-center m-5"><?= $articleInfo['Title'] ?></h2>
                <div class="container d-flex justify-content-center"><img src="upload/picture/<?= $articleInfo['Image'] ?>" class="img-fluid m-5" alt="Image de l'article"></div>
                <p class=""><?= $articleInfo['Content']  ?></p>
                <h6 class="mt-5 text-end">Article rédigé par : <?= $author['username'] ?></h6>
                <!-- Début travail sur les commentaires  -->
                <?php if (isset($_SESSION['id'])) {
                    $searchComment = $pdo->prepare("SELECT * FROM comments WHERE article_id = $getArticle ORDER BY date DESC");
                    $searchComment->execute();
                    $resultComment = $searchComment->fetchAll(PDO::FETCH_OBJ);
                    // Début boucle
                    foreach ($resultComment as $comment) {
                        var_dump($comment) ?>

                        <div class="card m-5" style="width: 18rem;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img src="upload/avatar/<?= $_SESSION['avatar'] ?>" alt="mdo" width="32" height="32" class="rounded-circle">
                                    <h5 class="card-title">Card title</h5>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                                <p class="card-text"><?= $comment->Comment ?></p>
                                <a href="#" class="card-link">Card link</a>
                                <a href="#" class="card-link">Another link</a>
                            </div>
                        </div>
                      <!-- Fin boucle -->
                    <?php }
                    ?>
                    <!-- Affichage du champ pour poster les commentaires -->
                    <form action="" method="POST">
                        <div class="form-group m-5">
                            <label for="comment">Contenu du commentaire</label>
                            <textarea class="form-control" id="comment" name="comment" rows="5"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="addComment" name="addComment" class="btn btn-success m-5">Ajouter un commentaire</button>
                        </div>
                    </form>
                    <!-- Fin travail sur les commentaires -->
                <?php }
            }
            if ($articleInfo['Statute'] !== "Validate") {
                if ($_SESSION['role'] !== ("Admin")) {
                    if ($_SESSION['id'] !== $articleInfo['User_id']) {
                        header("Location: index.php");
                    }
                }
            }
            if (isset($_SESSION['role']) && $articleInfo['Statute'] === "Pending" && ($_SESSION['role'] === "Admin" || $_SESSION['id'] === $articleInfo['User_id'])) {
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
                        <div class="form-group m-5">
                            <label for="report">Contenu du signalement</label>
                            <textarea class="form-control" id="report" name="report" rows="5"></textarea>
                        </div>
                    </form>
                <?php }
            }

            if (isset($_SESSION['id']) && $articleInfo['Statute'] === 'Saved' && ($_SESSION['id'] === $author['id'] || $_SESSION['role'] === "Admin")) { ?>
                <h2 class="text-center m-5"><?= $articleInfo['Title'] ?></h2>
                <div class="container d-flex justify-content-center"><img src="upload/picture/<?= $articleInfo['Image'] ?>" class="img-fluid m-5" alt="Image de l'article"></div>
                <p class=""><?= $articleInfo['Content']  ?></p>
                <h6 class="mt-5 text-end">Article rédigé par : <?= $author['username'] ?></h6>
                <?php if ($_SESSION['id'] === $author['id']) { ?>
                    <form action="" method="POST">
                        <div class="text-center">
                            <a class="btn btn-primary m-5" href="edit_article.php?id=<?= $articleInfo['Id']; ?>">Éditer votre article</a>
                            <button type="submit" name="publish" class="btn btn-success m-5">Publier l'article</button>
                            <button type="button" class="btn btn-danger m-5" data-bs-toggle="modal" data-bs-target="#exampleModal">Supprimer l'article</button>

                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Supprimer l'article</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Êtes-vous sur de vouloir supprimer cet article?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" name="delete" class="btn btn-danger">Supprimer l'article</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
            <?php
                }
            }
            ?>



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