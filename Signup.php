<?php
require_once('db.php');
require_once('functions.php');

// On vérifie si les superglobales sont définies,
if (isset($_POST["email"], $_POST["confirmEmail"], $_POST["username"], $_POST["password"], $_POST["confirmPassword"])) {
    // Si c'est le cas, on les affectes dans des variables pour les appelées plus facilement et on effectue un retrait des espaces puis une convertion des caractères spéciaux en entités HTML afin de prévenir des tentatives d'injections de code.
    $email = htmlspecialchars(trim($_POST["email"]));
    $confirmEmail = htmlspecialchars(trim($_POST["confirmEmail"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $confirmPassword = htmlspecialchars(trim($_POST["confirmPassword"]));

    // On vérifié que l'email et l'email de confirmation sont bien les mêmes,
    if ($email === $confirmEmail) {

        // Puis on filtre l'email pour vérifier sa validité.
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Si le mail est valide, nous allons vérifier qu'il n'existe pas dans la base de données :
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
                            // Si c'est bien le cas, on effectue un hachage du mot de passe,
                            $password = password_hash($password, PASSWORD_DEFAULT);

                            // On vérifie que les valeurs de la superglobale post on bien été initialisée,
                            if (!empty($_FILES['avatar']['name'])) {
                                // On attribue les différentes valeurs qui nous seront utiles par la suite dans des variables,
                                $tmpName = $_FILES['avatar']['tmp_name'];
                                $avatarName = $_FILES['avatar']['name'];
                                $avatarSize = $_FILES['avatar']['size'];
                                $avatarError = $_FILES['avatar']['error'];
                                // On créer un tableau qui aura pour dernière valeur l'extension du fichier,
                                $tabExtension = explode('.', $avatarName);
                                // On converti en minuscule la dernière valeur du tableau,
                                $extension = strtolower(end($tabExtension));
                                // On détermine les extensions autorisées dans une variable,
                                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                                // On détermine la taille maximum aurotisée pour le fichier,
                                $maxSize = 500000;
                                // Si dans le tableau l'extension correspond....
                                if (in_array($extension, $allowedExtensions)) {
                                    // Si le fichier ne dépasse pas la taille autorisée...
                                    if ($avatarSize <= $maxSize) {
                                        // Si aucune erreur n'est renvoyée..
                                        if ($avatarError === 0) {
                                            // On crée un nom unique pour le fichier,
                                            $uniqueName = uniqid();
                                            // On lui ajoute l'extension du fichier,
                                            $newAvatarName = $uniqueName . "." . $extension;
                                            // On déplace le fichier dans un dossier spécifique pour le récupérer,
                                            move_uploaded_file($tmpName, './upload/avatar/' . $newAvatarName);
                                            // Puis on prépare la requête pour l'insérer dans la base de données..
                                            $insertMember = $pdo->prepare("INSERT INTO users (email, username, passwords, avatar) VALUES (?,?,?,?)");
                                            $insertMember->execute([$email, $username, $password, $newAvatarName]);
                                            $error = "Votre compte à bien été crée !";
                                            header('Location: login.php');
                                        } else {
                                            $errorAvatar = "Une erreur s'est produite lors du téléchargement de votre fichier !";
                                        }
                                    } else {
                                        $errorAvatar = "Votre fichier est trop volumineux !";
                                    }
                                } else {
                                    $errorAvatar = "L'extension de votre image n'est pas valide !";
                                }
                            } else {
                                $defaultAvatar = "avatar.jpg";
                                // On prépare la requête pour insérer les informations dans la base de données..
                                $insertMember = $pdo->prepare("INSERT INTO users (email, username, passwords, avatar) VALUES (?,?,?,?)");
                                $insertMember->execute([$email, $username, $password, $defaultAvatar]);
                                $error = "Votre compte à bien été crée !";
                                header('Location: login.php');
                            }
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
            }
        } else {
            $error = "Votre adresse email est invalide !";
        }
    } else {
        $error = "Les adresses emails ne correspondent pas !";
    }
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
    <?php
    include_once('header.php');
    ?>
    <div class="container">
        <h2 class="text-center m-5">INSCRIPTION</h2>
        <form action="" method="POST" enctype="multipart/form-data">
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
            <div class="text-center m-5"><button type="submit" name="submit" class="btn btn-primary">Valider</button></div>
        </form>
        <?php
        if (isset($error)) {
            echo '<font color="red">' . $error . '</font>';
        }
        if (isset($errorAvatar)) {
            echo $errorAvatar;
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>