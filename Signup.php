<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=blog", "root", "root");
} catch (Exception $err) {
    die(('Erreur : ' . $err->getMessage()));
}



// On vérifie si les superglobales sont définies
if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    // Si c'est le cas, on les affectes dans des variables pour les appelées plus facilement
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    // var_dump($username);
    // var_dump($email);
    // var_dump($password);
    // On filtre l'email pour vérifier sa validité
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // S'il est valide, nous vérifions qu'il n'existe pas dans la base de données
        $email = htmlspecialchars(trim($email));
        $searchEmail = $pdo->prepare("SELECT email FROM user WHERE email = ?");
        $searchEmail->execute([$email]);
        $isEmailExist = $searchEmail->fetch();

        // S'il n'existe pas dans la base de données, ..
        if (!$isEmailExist) {
            $username = htmlspecialchars(trim($username));
            $searchUsername = $pdo->prepare("SELECT username FROM user WHERE username = ?");
            $searchUsername->execute([$username]);
            $isUsernameExist = $searchUsername->fetch();
            // Si l'username n'existe pas dans la base de données, ..
            if (!$isUsernameExist && strlen($username) >= 4) {
                // A REVOIR + REVOIR BDD?
                $password = htmlspecialchars((trim($password)));
                if (strlen($password) >= 8) {
                    $insert = $pdo->prepare("INSERT INTO user (email, username, password) VALUES (?,?,?)");
                    $insert->execute([$email, $username, $password]);
                } else {
                    echo "Le mots de passe doit contenir un minimum de 8 caractères";
                }
            } else {
                echo "Le nom d'utilisateur '$username' existe déjà, veuillez en choisir un autre";
            }
        } else {
            echo "L'adresse email '$email' est invalide";
        }
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
    <div class="container">
        <h1 class="text-center m-5">INSCRIPTION</h1>
        <form action="" method="POST">
            <div class="form-floating mb-3">
                <input required type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                <label for="email">Email address</label>
            </div>

            <div class="form-floating mb-3">
                <input required type="text" class="form-control" id="username" name="username" placeholder="Username">
                <label for="username">Username</label>
            </div>

            <div class="form-floating mb-3">
                <input required type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password">Password</label>
            </div>

            <div class="form-floating mb-3">
                <input required type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                <label for="confirmPassword">Confirm Password</label>
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Avatar</label>
                <input class="form-control" type="file" id="avatar" name="avatar">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>