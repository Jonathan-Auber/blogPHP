<?php
session_start();
require_once('db.php');
require_once('functions.php');

if (isset($_SESSION['id'], $_GET['id']) && $_GET['id'] > 0) {
    $getArticle = intval($_GET['id']);
    $reqArticle = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $reqArticle->execute([$getArticle]);
    $articleInfo = $reqArticle->fetch();

    if (isset($_POST['newTitle']) and !empty($_POST['newTitle']) and $_POST['newTitle'] != $articleInfo['Title']) {
        $newTitle = htmlspecialchars(trim($_POST['newTitle']));
        $searchTitle = $pdo->prepare("SELECT title FROM articles WHERE title = ?");
        $searchTitle->execute([$newTitle]);
        $isTitleExist = $searchTitle->fetch();
        if (!$isTitleExist) {
            $insertTitle = $pdo->prepare("UPDATE articles SET title = ? WHERE id = ?");
            $insertTitle->execute([$newTitle, $getArticle]);
            header("Location: article.php?id=" . $getArticle);
        } else {
            $error = "Ce titre existe déjà";
        }
    }


    if (isset($_POST['newContent']) and !empty($_POST['newContent'])) {
        $newContent = htmlspecialchars(trim($_POST['newContent']));
        $insertContent = $pdo->prepare("UPDATE articles SET content = ? WHERE id = ?");
        $insertContent->execute([$newContent, $getArticle]);
        header("Location: article.php?id=" . $getArticle);
    }





    if (!empty($_FILES['newPicture']['name'])) {
        var_dump($_FILES['newPicture']);
        $tmpName = $_FILES['newPicture']['tmp_name'];
        $newPictureName = $_FILES['newPicture']['name'];
        $newPictureSize = $_FILES['newPicture']['size'];
        $newPictureError = $_FILES['newPicture']['error'];
        $tabExtension = explode('.', $newPictureName);
        $extension = strtolower(end($tabExtension));
        $allowedExtensions = ['jpeg', 'jpg', 'png'];
        $maxSize = 500000;
        if (in_array($extension, $allowedExtensions)) {
            if ($newPictureSize <= $maxSize) {
                if ($newPictureError === 0) {
                    $uniqueName = uniqid();
                    $newPictureName = $uniqueName . "." . $extension;
                    move_uploaded_file($tmpName, './upload/Picture/' . $newPictureName);
                    $searchPreviousPicture = $pdo->prepare("SELECT image FROM articles WHERE id = ?");
                    $searchPreviousPicture->execute([$getArticle]);
                    $previousPicture = $searchPreviousPicture->fetch();
                    $previousPicture = current($previousPicture);
                    $insertnewPicture = $pdo->prepare("UPDATE articles SET image = ? WHERE id = ?");
                    $insertnewPicture->execute([$newPictureName, $getArticle]);
                    $removePreviousPicture = './upload/Picture/' . $previousPicture;
                    unlink($removePreviousPicture);
                    header("Location: article.php?id=" . $getArticle);
                } else {
                    $errorPicture = "Une erreur s'est produite lors du téléchargement de votre fichier !";
                }
            } else {
                $errorPicture = "Votre fichier est trop volumineux !";
            }
        } else {
            $errorPicture = "L'extension de votre image n'est pas valide !";
        }
    }




?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Article</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    </head>

    <body>
        <?php
        include_once('header.php');
        ?>
        <div class="container">
            <h2 class="text-center m-5">Éditer votre article</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Image -->
                <div class="mb-3">
                    <label for="picture" class="form-label">Photo pour illustrer l'article</label>
                    <input class="form-control" type="file" id="picture" name="newPicture">
                </div>
                <!-- Titre -->
                <div class="form-group mb-3">
                    <label for="title">Titre de l'article</label>
                    <input type="text" class="form-control" id="title" name="newTitle" value="<?= $articleInfo['Title'] ?>">
                </div>
                <!-- Contenu -->
                <div class="form-group mb-3">
                    <label for="content">Contenu de l'article</label>
                    <textarea class="form-control" id="content" name="newContent" rows="10"><?= $articleInfo['Content'] ?></textarea>
                </div>

                <!-- AJouter l'auteur à la bdd -->
                <div class="text-center"><button type="submit" name="submit" class="btn btn-primary">Modifier</button></div>
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
