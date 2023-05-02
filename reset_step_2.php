<?php
session_start();
require_once("db.php");

$getData = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$getData->execute([$_SESSION['user_id']]);
$userData = $getData->fetch(PDO::FETCH_ASSOC);


if (isset($_SESSION['timer'], $_SESSION['user_id'])) {
    if (time() >= $_SESSION['timer'] && time() <= ($_SESSION['timer'] + 600)) {
        if (isset($_POST['token'])) {
            $submitedToken = intval(htmlspecialchars(trim($_POST['token'])));
            $compareToken = $pdo->prepare("SELECT recovery_code FROM users WHERE id = ?");
            $compareToken->execute([$_SESSION['user_id']]);
            $token = $compareToken->fetch(PDO::FETCH_ASSOC);
            if ($token['recovery_code'] === $submitedToken) {
                header("Location: reset_step_3.php?id=" .$_SESSION['user_id']);
            }
        }
    } else {
        $error = "Le délais de 10 minutes est dépassé, veuillez recommencer le processus de <a href='reset_step_1.php'>récupération du mot de passe</a>.";
        $deleteToken = $pdo->prepare("UPDATE users SET recovery_code = NULL WHERE id = ?");
        $deleteToken->execute([$_SESSION["user_id"]]);
        $_SESSION = [];
        session_destroy();
    }




?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reset 2</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    </head>

    <body>
        <?php
        include_once('header.php');
        ?>
        <div class="container">
            <h2 class="text-center m-5">Confirmation du code de reinitialisation</h2>
            <form action="" method="POST">
                <div class="form-group mb-3">
                    <label for="token">Code à 5 chiffres</label>
                    <input required type="number" min="10000" max="99999" class="form-control" id="token" name="token" placeholder="Entrez le code">
                </div>
                <div class="text-center m-4"><button type="submit" name="submitToken" class="btn btn-primary">Valider</button></div>
            </form>
            <p class="text-danger text-center m-5"><?php if (isset($error)) {
                                                        echo $error;
                                                    } ?></p>


        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>

    </html>

<?php } else {
    header("Location: logout.php");
} ?>