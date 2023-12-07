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

    <h1>Socials</h1>

    <!-- Liste des utilisateurs -->
    <h2>Liste des utilisateurs</h2>
    <ul>
        <?php
        // Récupérez la liste des utilisateurs depuis votre base de données
        try {
            $db = new PDO("mysql:host=" . SQL_HOST . ";dbname=" . SQL_DBNAME . ";charset=utf8", SQL_USER, SQL_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->query("SELECT * FROM users");
            $users = $stmt->fetchAll();

            foreach ($users as $user) {
                echo "<li>{$user['username']} <a href='chat.php?user={$user['id']}'>Discuter</a></li>";
            }
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
        ?>
    </ul>

    <?php
    require_once('partials/footer.php');
    ?>
    <script src="style/js/app.js"></script>
</body>
</html>
