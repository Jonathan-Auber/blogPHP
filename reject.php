<?php
session_start();
require_once('db.php');

if (isset($_SESSION['id'], $_GET['id'])) {
    $getID = intval($_GET['id']);
    $getReport = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $getReport->execute([$getID]);
    $result = $getReport->fetch(PDO::FETCH_ASSOC);
    if ($_SESSION['id'] === $result['User_id']) {
?>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Login</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
        </head>
        <?php require_once('header.php'); ?>

        <body>
            <div class="container">
                <div class="d-flex justify-content-center">
                    <div class="card text-center w-50 h-50 m-5">
                        <div class="card-header">
                            <h5 class="card-title">Rapport</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= $result['Reporting'] ?></p>
                            <a href="profil.php?id=<?= $_SESSION['id'] ?>" class="btn btn-primary">Retour au profil</a>
                        </div>
                        <div class="card-footer text-muted">
                            <p class="text-danger">Cet article à été effacer, penser à récupérer le contenu avant de quitter la page si vous souhaitez le réutiliser.</p>
                        </div>
                    </div>
                </div>

                <h2 class="text-center m-5"><?= $result['Title'] ?></h2>
                <div class="container d-flex justify-content-center"><img src="upload/picture/<?= $result['Image'] ?>" class="img-fluid m-5" alt="Image de l'article"></div>
                <p class=""><?= $result['Content']  ?></p>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

        </body>

        </html>
<?php
        $deleteArticle = $pdo->prepare("DELETE FROM articles WHERE id = ?");
        $deleteArticle->execute([$result['Id']]);
    } else {
        header("Location: logout.php");
    }
    // Fin du if GET_ID
} else {
    header("Location: logout.php");
} ?>