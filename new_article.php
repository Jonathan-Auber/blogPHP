<?php
session_start();
require_once('db.php');

if (isset($_SESSION['id'])) {
    if (isset($_POST['title'], $_POST['content'])) {
        if (!empty($_POST['title']) || !empty($_POST['content'])) {
            $title = htmlspecialchars(trim($_POST['title']));
            $content = htmlspecialchars(trim($_POST['content']));
            $author = $_SESSION['id'];
            $compareTitle = $pdo->prepare("SELECT title FROM articles WHERE title = ?");
            $compareTitle->execute([$title]);
            $compareResult = $compareTitle->fetch();
            if (!$compareResult) {
                // Ajout du vérification de la longueur du titre, à vérifier
                if (strlen($title) < 100) {
                    $insertNewArticle = $pdo->prepare("INSERT INTO articles (title, content, user_id) VALUES (?, ?, ?)");
                    $insertNewArticle->execute([$title, $content, $author]);
                    $error = "Votre article est en attente de validation !";
                } else {
                    $error = "Le titre de votre article dépasse les 100 caractère !";
                }
            } else {
                $error = "Ce titre est déjà utilisé pour un autre article !";
            }
        } else {
            $error = "Veuillez remplir les champs";
        }
    }





?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>New Article</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    </head>

    <body>
        <?php
        include_once('header.php');
        ?>
        <div class="container">
            <h2 class="text-center m-5">Écrire un article</h2>
            <form action="" method="POST">
                <!-- Image -->
                <div class="mb-3">
                    <label for="picture" class="form-label">Photo pour illustrer l'article</label>
                    <input class="form-control" type="file" id="picture" name="picture">
                </div>
                <!-- Titre -->
                <div class="form-group mb-3">
                    <label for="title">Titre de l'article</label>
                    <input required type="text" class="form-control" id="title" name="title" placeholder="Entrez le titre de votre article">
                </div>
                <!-- Contenu -->
                <div class="form-group mb-3">
                    <label for="content">Contenu de l'article</label>
                    <textarea required class="form-control" id="content" name="content" rows="5" placeholder="Rédigez votre article ici"></textarea>
                </div>

                <!-- AJouter l'auteur à la bdd -->
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>

            <?php if (isset($error)) {
                echo $error;
            }
            ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>

    </html>

<?php
} else {
    header('Location: logout.php');
}
