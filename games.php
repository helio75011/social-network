<?php
require_once('config/settings.php');
// Vérifiez si l'utilisateur est connecté. Adaptez la condition selon votre logique de session.
// Par exemple, vérifiez si une certaine variable de session est définie.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirigez vers la page de connexion
    exit; // Arrêtez l'exécution du script
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
require_once('partials/head.php');
?>
<body>
    <?php
    require_once('partials/header.php');
    ?>


    <?php
    require_once('partials/footer.php');
    ?>
    <script src="style/js/app.js"></script>
</body>
</html>