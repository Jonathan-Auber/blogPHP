<?php
session_start();
require_once('db.php');

// if(!isset($_SESSION['timer'])) {} elseif timer ok renvoyer vers le mail/page token sinon destroy session
if (isset($_POST['emailRecup']) && !empty($_POST['emailRecup'])) {
    $userMail = htmlspecialchars(trim($_POST['emailRecup']));
    $compareMail = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $compareMail->execute([$userMail]);
    $isMailExist = $compareMail->fetch(PDO::FETCH_ASSOC);
    if ($isMailExist) {
        if ($isMailExist["Recovery_code"] === NULL) {
            $token = rand(10000, 99999);
            $insertToken = $pdo->prepare("UPDATE users SET recovery_code = ? WHERE id = ?");
            $insertToken->execute([$token, $isMailExist['Id']]);
            // ENVOIS DU MAIL
            // $to = $isMailExist['Email'];
            // $subject = "Réinitialisation du mot de passe";
            // $message = "Veuillez cliquer sur <a href='http://localhost:8888/blogPHP/reset_step_2.php'>ce lien</a> et rentrer le code suivant : " . $token;
            // mail($to, $subject, $message);
            // créer session 10min avec autre token ou pseudo
            $_SESSION['user_id'] = $isMailExist['Id'];
            $_SESSION['timer'] = time();
            $error = "Un mail à été envoyé à votre adresse email, veuillez suivre les instructions !";
        } else {
            $error = "Vous avez déjà fait une demande de réinitialisation en cours !";
        }
    } else {
        $error = "Cette email n'existe pas !";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body>
    <?php
    include_once('header.php');
    ?>
    <div class="container">
        <h2 class="text-center m-5">Réinitialisation du mot de passe</h2>
        <form action="" method="POST">
            <div class="form-group mb-3">
                <label for="email">Adresse email</label>
                <input required type="email" class="form-control" id="email" name="emailRecup" placeholder="name@example.com" value="<?php if (isset($email)) {
                                                                                                                                        } ?>">
            </div>
            <div class="text-center m-4"><button type="submit" name="submitMail" class="btn btn-primary">Réinitialiser le mot de passe</button></div>
        </form>
        <p class="text-danger text-center m-5"><?php if (isset($error)) {
                                                    echo $error;
                                                } ?></p>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>