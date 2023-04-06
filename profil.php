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
            <br><br>
            Pseudo = <?php echo $userInfo["Username"]; ?>
            <br><br>
            Mail = <?php echo $userInfo["Email"]; ?>
            <br><br>
            <?php if (isset($_SESSION['id']) and $userInfo['Id'] === $_SESSION['id']) {
            ?>
                <a href="new_article.php">Ecrire un article</a>
                <br>
                <a href="profil_edit.php">Editer mon profil</a>
                <br>
                <a href="logout.php">Se déconnecter</a>
            <?php } ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>

    </html>

<?php } ?>