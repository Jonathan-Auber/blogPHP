<?php
// On appel la base de données, ou on renvoie les erreurs s'il y en as.
require_once('db.php');
require_once('functions.php');


// On vérifie si les superglobales sont définies,
if (isset($_POST["email"], $_POST["confirmEmail"], $_POST["username"], $_POST["password"], $_POST["confirmPassword"])) {
    // Si c'est le cas, on les affectes dans des variables pour les appelées plus facilement et on retire les espaces et on convertit les caractères spéciaux en entités HTML afin de prévenir des tentatives d'injections de code.
    $email = htmlspecialchars(trim($_POST["email"]));
    $confirmEmail = htmlspecialchars(trim($_POST["confirmEmail"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $confirmPassword = htmlspecialchars(trim($_POST["confirmPassword"]));

    if ($email === $confirmEmail) {
        // On filtre l'email pour vérifier sa validité,
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // Si le mail est valide, nous allons vérifier qu'il n'existe pas dans la base de données,
            // On prépare la requête dans une variable,
            $searchEmail = $pdo->prepare("SELECT email FROM users WHERE email = ?");
            // On execute la requête,
            $searchEmail->execute([$email]);
            // Enfin on récupère le résultat dans une variable dont la valeur est un booléen.
            $isEmailExist = $searchEmail->fetch();

            // S'il n'existe pas dans la base de données (= le booléen renvoie false),
            if (!$isEmailExist) {
                // On prépare la requête dans une variable,
                $searchUsername = $pdo->prepare("SELECT username FROM users WHERE username = ?");
                // On execute la requête,
                $searchUsername->execute([$username]);
                // Enfin on récupère le résultat dans une variable dont la valeur est un booléen.
                $isUsernameExist = $searchUsername->fetch();
                // Si l'username n'existe pas dans la base de données (= le booléen renvoie false),
                if (!$isUsernameExist) {
                    // Et si la longueur du pseudonyme est la bonne
                    if (strlen($username) >= 4 && strlen($username) <= 255) {
                        // On vérifie que le mot de passe comporte au moins 8 caractères dont 1 minuscule, 1 majuscule, 1 chiffre et un caractère spécial et aussi que les mots de passes entrés dans le formulaire sont bien identiques,
                        if (isValidPassword($password) && $password === $confirmPassword) {
                            // TO BE CONTINUED
                            $password = password_hash($password, PASSWORD_DEFAULT);
                            $insertMember = $pdo->prepare("INSERT INTO users (email, username, passwords) VALUES (?,?,?)");
                            $insertMember->execute([$email, $username, $password]);
                            $error = "Votre compte à bien été crée !";
                        } elseif ($password !== $confirmPassword) {
                            $error = "Les mots de passe rentrer lors du processus sont différents";
                        } else {
                            $error = "Le mots de passe doit contenir un minimum de 8 caractères, une majuscule et un caractère spécial";
                        }
                    } else {
                        $error = "Votre pseudonyme est trop court ou trop long";
                    }
                } else {
                    $error = "Le nom d'utilisateur '$username' existe déjà, veuillez en choisir un autre";
                }
            } else {
                $error = "Cette adresse email '$email' existe déjà";
                // header('Location: login.php');
            }
        } else {
            $error = "Votre adresse email est invalide !";
        }
    } else {
        $error = "Les adresses emails ne correspondent pas !";
    }
} elseif (empty($_POST["email"] and $_POST["confirmEmail"] and $_POST["username"] and $_POST["password"] and $_POST["confirmPassword"])) {
    $error = "Tous les champs ne sont pas remplis !";
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h2 class="text-center m-5">INSCRIPTION</h2>
        <form action="" method="POST">
            <div class="form-group mb-3">
                <label for="email">Adresse email</label>
                <input required type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?php if (isset($email)) {
                                                                                                                                    echo $email;
                                                                                                                                } ?>">
                <small id="emailHelp" class="form-text text-muted">Nous ne transmettrons jamais votre email.</small>
            </div>

            <div class="form-group mb-3">
                <label for="confirmEmail">Confirmation de l'adresse email</label>
                <input required type="email" class="form-control" id="confirmEmail" name="confirmEmail" placeholder="name@example.com" value="<?php if (isset($confirmEmail)) {
                                                                                                                                                    echo $confirmEmail;
                                                                                                                                                } ?>">
            </div>

            <div class="form-group mb-3">
                <label for="username">Pseudonyme</label>
                <input required type="text" class="form-control" id="username" name="username" placeholder="Pseudonyme" value="<?php if (isset($username)) {
                                                                                                                                    echo $username;
                                                                                                                                } ?>">
                <small id="usernameHelp" class="form-text text-muted">Votre pseudonyme doit comporter un minimum de 4 caractères.</small>

            </div>

            <div class="form-group mb-3">
                <label for="password">Mot de passe</label>
                <input required type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                <small id="passwordHelp" class="form-text text-muted">Votre mot de passe doit être composé d'un minimum de 8 caractères et contenir au mois 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.</small>

            </div>

            <div class="form-group mb-3">
                <label for="confirmPassword">Confirmation du mot de passe</label>
                <input required type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe">
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Avatar</label>
                <input class="form-control" type="file" id="avatar" name="avatar">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
        <?php
        if (isset($error)) {
            echo '<font color="red">' . $error . '</font>';
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>