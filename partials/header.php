<?php

require('head.php');

// Traitement de la déconnexion
if (isset($_POST['logout'])) {
    session_destroy(); // Détruit la session
    header('Location: login.php'); // Redirige vers la page de connexion
    exit();
}

?>

<header>
    <div id="logo">
        <img src="style/uploads/logo-mds.png" alt="">
        <h1>LES DETER<b style="color: #e51c32;">S</b></h1>
    </div>

    <ul>
        <li><a href="index.php">home</a></li>
        <li><a href="socials.php">socials</a></li>
        <li><a href="games.php">games</a></li>
        <li><a href="games.php">playlist</a></li>
        <li><a href="profile.php">profile</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="" method="post">
                <input type="submit" name="logout" value="Déconnexion">
            </form>
        <?php endif; ?>
    </ul>
</header>