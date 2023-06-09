<?php
session_start();
require_once('db.php');
require_once('functions.php');

if (isset($_GET['id']) && (intval($_GET['id']) === $_SESSION['id'] || $_SESSION['role'] === "Admin")) {
    $reqUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $reqUser->execute([$_GET['id']]);
    $user = $reqUser->fetch();

    if (isset($_POST['newUsername']) and !empty($_POST['newUsername']) and $_POST['newUsername'] != $user['Username']) {
        $newUsername = htmlspecialchars(trim($_POST['newUsername']));
        $searchUsername = $pdo->prepare("SELECT username FROM users WHERE username = ?");
        $searchUsername->execute([$newUsername]);
        $isUsernameExist = $searchUsername->fetch();
        if (!$isUsernameExist) {
            $insertUsername = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            $insertUsername->execute([$newUsername, $_GET['id']]);
            header("Location: profil.php?id=" . $_GET['id']);
        } else {
            $error = "Ce pseudonyme est déjà utilisé";
        }
    }


    if (isset($_POST['newEmail']) and !empty($_POST['newEmail']) and $_POST['newEmail'] !== $user['Email']) {
        $newEmail = htmlspecialchars(trim($_POST['newEmail']));
        $searchEmail = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $searchEmail->execute([$newEmail]);
        $isEmailExist = $searchEmail->fetch();
        if (!$isEmailExist) {
            if ($_POST['newEmail'] === $_POST['confirmNewEmail']) {
                if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                    $insertEmail = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
                    $insertEmail->execute([$newEmail, $_GET['id']]);
                    header("Location: profil.php?id=" . $_GET['id']);
                } else {
                    $error = "Le mots de passe doit contenir un minimum de 8 caractères, une majuscule et un caractère spécial";
                }
            } else {
                $error = "Vous n'avez pas saisi les mêmes adresses email !";
            }
        } else {
            $error = "Cette adresse Email existe déjà !";
        }
    }



    if (isset($_POST['newPassword'], $_POST['confirmNewPassword'], $_POST['oldPassword']) && !empty($_POST['newPassword']) && !empty($_POST['confirmNewPassword']) && !empty($_POST['oldPassword'])) {
        $oldPassword = htmlspecialchars(trim($_POST['oldPassword']));
        if (password_verify($oldPassword, $user['Passwords'])) {
            $newPassword = htmlspecialchars(trim(($_POST['newPassword'])));
            $confirmNewPassword = htmlspecialchars(trim($_POST['confirmNewPassword']));
            if ($newPassword === $confirmNewPassword) {
                if (isValidPassword($newPassword)) {
                    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $insertPassword = $pdo->prepare("UPDATE users SET passwords = ? WHERE id = ?");
                    $insertPassword->execute([$newPassword, $_GET['id']]);
                    header("Location: profil.php?id=" . $_GET['id']);
                } else {
                    $error = "Le mots de passe doit contenir un minimum de 8 caractères, une majuscule et un caractère spécial";
                }
            } else {
                $error = "Les  nouveaux mots de passe saisis ne correspondent pas !";
            }
        } else {
            $error = "Votre ancien mot de passe ne correspond pas !";
        }
    }

    if (!empty($_FILES['newAvatar']['name'])) {
        $tmpName = $_FILES['newAvatar']['tmp_name'];
        $newAvatarName = $_FILES['newAvatar']['name'];
        $newAvatarSize = $_FILES['newAvatar']['size'];
        $newAvatarError = $_FILES['newAvatar']['error'];
        $tabExtension = explode('.', $newAvatarName);
        $extension = strtolower(end($tabExtension));
        $allowedExtensions = ['jpeg', 'jpg', 'png'];
        $maxSize = 500000;
        if (in_array($extension, $allowedExtensions)) {
            if ($newAvatarSize <= $maxSize) {
                if ($newAvatarError === 0) {
                    $uniqueName = uniqid();
                    $newAvatarName = $uniqueName . "." . $extension;
                    move_uploaded_file($tmpName, './upload/avatar/' . $newAvatarName);
                    $searchPreviousAvatar = $pdo->prepare("SELECT avatar FROM users WHERE id = ?");
                    $searchPreviousAvatar->execute([$_GET['id']]);
                    $previousAvatar = $searchPreviousAvatar->fetch();
                    $previousAvatar = current($previousAvatar);
                    $insertNewAvatar = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                    $insertNewAvatar->execute([$newAvatarName, $_GET['id']]);
                    if ($previousAvatar !== "avatar.png") {
                        $removePreviousAvatar = './upload/avatar/' . $previousAvatar;
                        unlink($removePreviousAvatar);
                        header("Location: profil.php?id=" . $_GET['id']);
                    }
                } else {
                    $errorAvatar = "Une erreur s'est produite lors du téléchargement de votre fichier !";
                }
            } else {
                $errorAvatar = "Votre fichier est trop volumineux !";
            }
        } else {
            $errorAvatar = "L'extension de votre image n'est pas valide !";
        }
    }
    // A voir si on garde cette condition !!!
    // if (isset($_POST['newUsername']) and $_POST['newUsername'] === $user['Username']) {
    // header("Location: profil.php?id=" . $_GET['id']);
    // }



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
            <h2 class="text-center m-5">Edition de mon profil</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="username">Pseudonyme</label>
                    <input type="text" class="form-control" id="username" name="newUsername" placeholder="Votre nouveau pseudonyme" value="<?php echo $user['Username']; ?>">
                </div>
                <hr class="m-5">
                <div class="form-group mb-3">
                    <label for="email">Adresse email</label>
                    <input type="email" class="form-control" id="email" name="newEmail" placeholder="Votre nouvel email" value="<?php echo $user['Email'] ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="confirmEmail">Confirmez votre adresse email</label>
                    <input type="email" class="form-control" id="confirmEmail" name="confirmNewEmail" placeholder="Confirmez votre nouvel email" value="<?php echo $user['Email'] ?>">
                </div>
                <hr class="m-5">

                <div class="form-group mb-3">
                    <label for="oldPassword">Ancien mot de passe</label>
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Confirmez votre ancien mot de passe">
                </div>

                <div class="form-group mb-3">
                    <label for="newPassword">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Votre nouveau mot de passe">
                </div>

                <div class="form-group mb-3">
                    <label for="confirmPassword">Confirmez votre mot de passe</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmNewPassword" placeholder="Confirmez votre nouveau mot de passe">
                </div>
                <hr class="m-5">

                <div class="mb-3">
                    <label for="newAvatar" class="form-label">Avatar</label>
                    <input class="form-control" type="file" id="newAvatar" name="newAvatar">
                </div>
                <div class="text-center"><button type="submit" name="newSubmit" class="btn btn-primary m-5">Mettre à jour</button></div>
            </form>
            <?php if (isset($error)) {
                echo $error;
                if (isset($errorAvatar)) {
                    echo $errorAvatar;
                }
            }
            ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>

    </html>

<?php } else {
    header("Location: logout.php");
}
