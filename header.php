<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <!-- <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap" />
                </svg>
            </a> -->

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <!-- <li><a href="index.php" class="nav-link px-2 link-secondary">Accueil</a></li> -->
                <li><a href="index.php" class="nav-link px-2 link-dark">Accueil</a></li>
                <!-- <li><a href="#" class="nav-link px-2 link-dark">Customers</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">Products</a></li> -->
            </ul>

            <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form> -->
            <?php if (isset($_SESSION['id'])) { ?>
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="upload/avatar/<?= $_SESSION['avatar'] ?>" alt="mdo" width="32" height="32" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu text-small">
                        <li><a class="dropdown-item" href="new_article.php">Nouvel article</a></li>
                        <li><a class="dropdown-item" href="profil.php?id=<?= $_SESSION['id'] ?>">Profil de <?= $_SESSION['username'] ?></a></li>
                        <li><a class="dropdown-item" href="profil_edit.php?id=<?= $_SESSION['id'] ?>">Éditer mon profil</a></li>
                        <?php if ($_SESSION['role'] === "Admin") { ?>
                            <li><a class="dropdown-item" href="admin.php?id=">Espace administrateur</a></li>
                        <?php } ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Se déconnecter</a></li>
                    </ul>
                </div>
            <?php } else { ?>
                <nav class="navbar navbar-expand-lg">
                    <ul class="navbar-nav d-flex align-items-center">
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Se connecter</a>
                        </li>
                        <li class="nav-item">
                            <a href="signup.php" class="nav-link">S'inscrire</a>
                        </li>
                    </ul>
                </nav>
            <?php } ?>
        </div>
    </div>
</header>

<!-- <?php
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
<?php } ?> -->