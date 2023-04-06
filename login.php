<?php
session_start();
require_once('db.php');

if (isset($_POST["emailConnect"], $_POST["passwordConnect"])) {
    $email = htmlspecialchars(trim($_POST["emailConnect"]));
    $password = htmlspecialchars(trim($_POST["passwordConnect"]));
    $queryDB = 'SELECT * FROM users WHERE email = ?';
    $response = $pdo->prepare($queryDB);
    $response->execute([$email]);
    $results = $response->fetchAll();
    foreach ($results as $data) {
        if ($data['Email'] === $email && password_verify($password, $data['Passwords'])) {
            $_SESSION['id'] = $data['Id'];
            $_SESSION['email'] = $data['Email'];
            $_SESSION['username'] = $data['Username'];
            if ($data["Role"] === "Admin") {
                header('Location: admin.php');
            } else {
                header('Location: profil.php?id='.$_SESSION["id"]);
            }
            // header('Location: index.php');
        } else {
            $error = 'Votre adresse email ou votre mot de passe ne correspond pas !';
        }
    }
}

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
    <div class="container">
        <h2 class="text-center m-5">CONNECTION</h2>
        <form action="" method="POST">
            <div class="form-group mb-3">
                <label for="email">Adresse email</label>
                <input required type="email" class="form-control" id="email" name="emailConnect" placeholder="name@example.com" value="<?php if (isset($email)) {
                                                                                                                                            echo $email;
                                                                                                                                        } ?>">
            </div>

            <div class="form-group mb-3">
                <label for="password">Mot de passe</label>
                <input required type="password" class="form-control" id="password" name="passwordConnect" placeholder="Mot de passe">
            </div>
            <button type="submit" name="submitConnect" class="btn btn-primary">Submit</button>
        </form>
        <?php if (isset($error)) {
            echo $error;
        } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>