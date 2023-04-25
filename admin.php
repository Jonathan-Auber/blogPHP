<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['id']) || $_SESSION['role'] !== "Admin") {
    header('Location: logout.php');
} else {
    $selectUser = $pdo->prepare("SELECT * FROM users");
    $selectUser->execute();
    $allUser = $selectUser->fetchAll(PDO::FETCH_OBJ);
    // var_dump($allUser);
    // Séparer les requêtes
    $selectArticleValid = $pdo->prepare("SELECT * FROM articles WHERE statute = ?");
    $validate = "validate";
    $selectArticleValid->execute([$validate]);
    $allValidArticle = $selectArticleValid->fetchAll(PDO::FETCH_OBJ);
    // var_dump($allValidArticle);
    $selectPendingArticle = $pdo->prepare("SELECT * FROM articles WHERE statute = ?");
    $pending = "Pending";
    $selectPendingArticle->execute([$pending]);
    $allPendingArticle = $selectPendingArticle->fetchALL(PDO::FETCH_OBJ);
    // var_dump($allPendingArticle);
    $selectUpdateArticle = $pdo->prepare("SELECT * FROM articles_update");
    $selectUpdateArticle->execute();
    $allUpdateArticle = $selectUpdateArticle->fetchAll(PDO::FETCH_OBJ);
    // var_dump($allUpdateArticle);
    $selectAllArticle = $pdo->prepare("SELECT * FROM articles");
    $selectAllArticle->execute();
    $allArticle = $selectAllArticle->fetchAll(PDO::FETCH_OBJ);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">

</head>

<body>
    <?php require_once('header.php'); ?>
    <div class="container">
        <div class="d-flex justify-content-center m-5">
            <select class="form-select w-50" aria-label="Default select example">
                <option selected>Selectionnez votre contenu</option>
                <option value="1">Utilisateurs</option>
                <option value="2">Nouveaux articles en attente de validation</option>
                <option value="3">Articles modifiés en attente de validation</option>
                <option value="4">Articles validés</option>
                <option value="5">Gérer les articles</option>
            </select>
        </div>
        <div id="user">
            <h4 class="text-center mt-5">Utilisateurs</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 4%;">#</th>
                        <th scope="col">Nom d'utilisateur</th>
                        <th scope="col">Adresse email</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rowUser = 1;
                    foreach ($allUser as $user) {

                    ?>
                        <tr>
                            <th class="align-middle" scope="row"><?= $rowUser ?></th>
                            <td class="align-middle"><?= $user->Username ?></td>
                            <td class="align-middle"><?= $user->Email ?></td>
                            <td class="align-middle text-end">
                                <a class="btn btn-success m-1" href="profil.php?id=<?= $user->Id ?>" role="button">Gérer l'utilisateur</a>
                            </td>
                        </tr>
                    <?php
                        $rowUser++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="pending">
            <h4 class="text-center mt-5">Nouveaux articles en attente</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 4%;">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rowPending = 1;
                    foreach ($allPendingArticle as $pendingArticle) {

                    ?>
                        <tr>
                            <th class="align-middle" scope="row"><?= $rowPending ?></th>
                            <td class="align-middle"><?= $pendingArticle->Title ?></td>
                            <td class="align-middle text-end">
                                <a class="btn btn-success m-1" href="article.php?id=<?= $pendingArticle->Id ?>" role="button">Voir l'article</a>
                            </td>
                        </tr>
                    <?php
                        $rowPending++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="update">
            <h4 class="text-center mt-5">Articles modifiés en attente</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 4%;">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rowUpdate = 1;
                    foreach ($allUpdateArticle as $updateArticle) {

                    ?>
                        <tr>
                            <th class="align-middle" scope="row"><?= $rowUpdate ?></th>
                            <td class="align-middle"><?= $updateArticle->Title ?></td>
                            <td class="align-middle text-end">
                                <a class="btn btn-success m-1" href="article_updated.php?id=<?= $updateArticle->Article_id ?>" role="button">Voir l'article</a>
                            </td>
                        </tr>
                    <?php
                        $rowUpdate++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="valid">
            <h4 class="text-center mt-5">Articles validés</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 4%;">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rowValid = 1;
                    foreach ($allValidArticle as $validArticle) {

                    ?>
                        <tr>
                            <th class="align-middle" scope="row"><?= $rowValid ?></th>
                            <td class="align-middle"><?= $validArticle->Title ?></td>
                            <td class="align-middle text-end">
                                <a class="btn btn-primary m-1" href="edit_article.php?id=<?= $validArticle->Id ?>" role="button">Éditer l'article</a>
                                <a class="btn btn-success m-1" href="article.php?id=<?= $validArticle->Id ?>" role="button">Voir l'article</a>
                            </td>
                        </tr>
                    <?php
                        $rowValid++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="allArticles">
            <h4 class="text-center mt-5">Tous les articles</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 4%;">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Statut</th>

                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rowArticle = 1;
                    $modal = 1;
                    foreach ($allArticle as $article) {

                    ?>
                        <tr>
                            <th class="align-middle" scope="row"><?= $rowArticle ?></th>
                            <td class="align-middle"><?= $article->Title ?></td>
                            <td class="align-middle"><?= $article->Id ?></td>
                            <td class="align-middle text-end">
                                <form action="delete_article.php?id=<?= $article->Id ?>" method="POST">
                                    <a class="btn btn-primary m-1" href="edit_article.php?id=<?= $article->Id ?>" role="button">Éditer l'article</a>
                                    <a class="btn btn-success m-1" href="article.php?id=<?= $article->Id ?>" role="button">Voir l'article</a>
                                    <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modal<?= $modal ?>">Supprimer l'article</button>
                                    <div class="modal fade" id="modal<?= $modal ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                    <button type="submit" name="deleteArticle" class="btn btn-danger">Supprimer l'article</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php
                        $rowArticle++;
                        $modal++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>