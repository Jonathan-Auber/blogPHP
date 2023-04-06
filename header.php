<header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Accueil</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"></a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <?php
                        if (isset($_SESSION['id'])) {
                        ?>
                            <li class="nav-item">
                                <a href="profil.php?id=<?= $_SESSION['id'] ?>" class="nav-link">Accéder au profil de <?= $_SESSION['username'] ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="logout.php" class="nav-link">Se déconnecter</a>
                            </li>
                            
                        <?php  } else { ?>

                            <li class="nav-item">
                                <a href="login.php" class="nav-link">Se connecter</a>
                            </li>
                            <li class="nav-item">
                                <a href="signup.php" class="nav-link">S'inscrire</a>
                            </li>
                        <?php } ?>
                    </ul>

                </div>
            </div>
        </nav>
    </header>