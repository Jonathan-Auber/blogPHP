<?php
session_start();
require_once("functions.php");
require_once("db.php");

if (isset($_GET['id'], $_SESSION['user_id']) && intval($_GET['id']) === $_SESSION['user_id']) {
    if (time() >= $_SESSION['timer'] && time() <= ($_SESSION['timer'] + 600)) {
        if (isset($_POST['newRecoveryPassword'], $_POST['confirmRecoveryPassword']) && !empty($_POST['newRecoveryPassword']) && !empty($_POST['confirmRecoveryPassword'])) {
            $newRecoveryPassword = htmlspecialchars(trim(($_POST['newRecoveryPassword'])));
            $confirmRecoveryPassword = htmlspecialchars(trim($_POST['confirmRecoveryPassword']));
            if ($newRecoveryPassword === $confirmRecoveryPassword) {
                if (isValidPassword($newRecoveryPassword)) {
                    $newRecoveryPassword = password_hash($newRecoveryPassword, PASSWORD_DEFAULT);
                    $insertPassword = $pdo->prepare("UPDATE users SET passwords = ?, recovery_code = NULL WHERE id = ?");
                    $insertPassword->execute([$newRecoveryPassword, $_SESSION['user_id']]);
                    $_SESSION = [];
                    session_destroy();
                    header("Location: login.php");
                } else {
                    $error = "Le mots de passe doit contenir un minimum de 8 caractères, une majuscule et un caractère spécial";
                }
            } else {
                $error = "Les  nouveaux mots de passe saisis ne correspondent pas !";
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
            <form action="" method="POST"">
    
    <div class=" form-group mb-3">
                <label for="newRecoveryPassword">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="newRecoveryPassword" name="newRecoveryPassword" placeholder="Votre nouveau mot de passe">
        </div>

        <div class="form-group mb-3">
            <label for="confirmRecoveryPassword">Confirmez votre mot de passe</label>
            <input type="password" class="form-control" id="confirmRecoveryPassword" name="confirmRecoveryPassword" placeholder="Confirmez votre nouveau mot de passe">
        </div>

        <div class="text-center"><button type="submit" name="recoverySubmit" class="btn btn-primary m-5">Mettre à jour</button></div>
        </form>
        <p class="text-danger text-center m-5"><?php if (isset($error)) {
                                                    echo $error;
                                                } ?></p>


        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>

    </html>

<?php } else {
    header("Logout.php");
}
