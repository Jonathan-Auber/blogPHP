<?php
session_start();
require_once('db.php');

if (isset($_GET['id']) and $_GET['id'] > 0) {
    $getId = intval($_GET['id']);
    $reqUser = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $reqUser->execute([$getId]);
    $userInfo = $reqUser->fetch();

?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    </head>

    <body>
        <?php
        include_once('header.php');
        ?>
        <div class="container">
            <h2 class="text-center m-5"><?php echo "Profil de" . " " . $userInfo['Username']; ?></h2>
            <!-- Balise de style intégrée pour les photos de profil -->
            <div class="text-center"><img class="rounded mx-auto d-block" style="max-width: 24%;" src="upload/avatar/<?= $userInfo['Avatar'] ?>" alt="Image de profil"></div>
            <br>
            <br>

            <?php
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $searchArticles = $pdo->prepare("SELECT id, title, user_id, statute FROM articles WHERE user_id = ? ORDER BY id DESC");
                $searchArticles->execute([$_GET['id']]);
                $userArticles = $searchArticles->fetchAll();
                // On initialise des variable que l'on va incrémenter juste en dessous,
                $articleValidate = 0;
                $articleSaved = 0;
                $articlePending = 0;
                $articleRejected = 0;
                // On va parcourir le tableau obtenu et initialiser des variables pour les statuts dont nous aurons besoin par la suite.
                foreach ($userArticles as $article) {
                    if ($article['statute'] === "Validate") {
                        $articleValidate++;
                    } elseif ($article['statute'] === "Pending") {
                        $articlePending++;
                    } elseif ($article['statute'] === "Saved") {
                        $articleSaved++;
                    } elseif ($article['statute'] === "Rejected") {
                        $articleRejected++;
                    }
                }
                // AFFICHAGE DES ARTICLES VALIDÉS.
                if ($articleValidate > 0) {
                    $rowValidate = 1;
            ?>
                    <h4 class="text-center mt-5"><?php echo "Dernier articles de " . $userInfo["Username"]; ?></h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 4%;">#</th>
                                <th scope="col">Titre</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userArticles as $article) {
                                if ($article['statute'] === "Validate") {
                            ?>
                                    <tr>
                                        <th class="align-middle" scope="row"><?= $rowValidate ?></th>
                                        <td class="align-middle"><?= $article['title'] ?></td>
                                        <td class="align-middle text-end">
                                            <a class="btn btn-primary m-1" href="edit_article.php?id=<?= $article['id'] ?>" role="button">Éditer l'article</a>
                                            <a class="btn btn-success m-1" href="article.php?id=<?= $article['id'] ?>" role="button">Voir l'article</a>
                                        </td>
                                    </tr>
                            <?php
                                    $rowValidate++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php }
                //  AFFICHAGE DES ARTICLES SAUVEGARDÉS
                if (isset($_SESSION['id']) && $_SESSION['id'] === intval($_GET['id'])) {
                    if ($articleSaved > 0) {
                        $rowSaved = 1; ?>
                        <h4 class="text-center mt-5">Mes articles en cours</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 4%;">#</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($userArticles as $article) {
                                    if ($article['statute'] === "Saved") {
                                ?>
                                        <tr>
                                            <th class="align-middle" scope="row"><?= $rowSaved ?></th>
                                            <td class="align-middle"><?= $article['title'] ?></td>
                                            <td class="align-middle text-end">
                                                <a class="btn btn-primary m-1" href="edit_article.php?id=<?= $article['id'] ?>" role="button">Éditer l'article</a>
                                                <a class="btn btn-success m-1" href="article.php?id=<?= $article['id'] ?>" role="button">Voir l'article</a>
                                            </td>
                                        </tr>
                                <?php
                                        $rowSaved++;
                                    }
                                } ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    // AFFICHAGE DES ARTICLES EN ATTENTE
                    if ($articlePending > 0) {
                        $rowPending = 1; ?>
                        <h4 class="text-center mt-5">Mes articles en attente de validation</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 4%;">#</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($userArticles as $article) {
                                    if ($article['statute'] === "Pending") {
                                ?>
                                        <tr>
                                            <th class="align-middle" scope="row"><?= $rowPending ?></th>
                                            <td class="align-middle"><?= $article['title'] ?></td>
                                            <td class="align-middle text-end"><a class="btn btn-warning m-1" href="article.php?id=<?= $article['id'] ?>" role="button">En attente</a></button></td>

                                        </tr>
                                <?php
                                        $rowPending++;
                                    }
                                } ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    // AFFICHAGE DES ARTICLES REJETÉS
                    if ($articleRejected > 0) {
                        $rowRejected = 1; ?>
                        <h4 class="text-center mt-5">Mes articles rejeté</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 4%;">#</th>
                                    <th scope="col">Titre</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($userArticles as $article) {
                                    if ($article['statute'] === "Rejected") {
                                ?>
                                        <tr>
                                            <th class="align-middle" scope="row"><?= $rowRejected ?></th>
                                            <td class="align-middle"><?= $article['title'] ?></td>
                                            <td class="align-middle text-end"><a class="btn btn-danger m-1" href="reject.php?id=<?= $article['id'] ?>" role="button">Voir le détail</a></button></td>

                                        </tr>
                                <?php
                                        $rowRejected++;
                                    }
                                } ?>
                            </tbody>
                        </table>
                <?php
                    }
                }

                ?>

                <!-- Fin du if get id -->
            <?php } ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>

    </html>

<?php } ?>