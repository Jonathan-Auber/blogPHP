<?php
session_start();
require_once('db.php');

if (isset($_SESSION['id'])) {
    if (isset($_POST['title'], $_POST['content'], $_FILES['picture'])) {
        if (!empty($_POST['title']) || !empty($_POST['content'])) {
            $tmpName = $_FILES['picture']['tmp_name'];
            $fileName = $_FILES['picture']['name'];
            $fileSize = $_FILES['picture']['size'];
            $fileError = $_FILES['picture']['error'];
            $title = htmlspecialchars(trim($_POST['title']));
            $content = htmlspecialchars(trim($_POST['content']));
            $author = $_SESSION['id'];
            $compareTitle = $pdo->prepare("SELECT title FROM articles WHERE title = ?");
            $compareTitle->execute([$title]);
            $compareResult = $compareTitle->fetch();
            if (!$compareResult) {
                if (strlen($title) < 100) {
                    $tabExtension = explode('.', $fileName);
                    $extension = strtolower(end($tabExtension));
                    $allowedExtensions = ['jpg', 'png', 'jpeg'];
                    $maxSize = 500000;
                    if (in_array($extension, $allowedExtensions)) {
                        if ($fileSize <= $maxSize) {
                            if ($fileError === 0) {
                                $uniqueName = uniqid();
                                $newFileName = $uniqueName . "." . $extension;
                                move_uploaded_file($tmpName, './upload/picture/' . $newFileName);
                                $insertNewArticle = $pdo->prepare("INSERT INTO articles (title, content, image, user_id) VALUES (?, ?, ?, ?)");
                                $insertNewArticle->execute([$title, $content, $newFileName, $author]);
                                // Effectuer une redirection?
                                $error = "Votre article est en attente de validation !";
                            } else {
                                $error = "Une erreur s'est produite lors du téléchargement de votre fichier !";
                            }
                        } else {
                            $error = "Votre fichier est trop volumineux !";
                        }
                    } else {
                        $error = "L'extension de votre image n'est pas valide !";
                    }
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
            <form action="" method="POST" enctype="multipart/form-data">
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
