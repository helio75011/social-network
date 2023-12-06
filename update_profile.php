<?php
require_once('config/settings.php');
if (!session_id()) session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Connexion à la base de données
    try {
        $db = new PDO("mysql:host=" . SQL_HOST . ";dbname=" . SQL_DBNAME . ";charset=utf8", SQL_USER, SQL_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Mise à jour des informations de l'utilisateur
        $stmt = $db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $_SESSION['user_id']]);

        // Redirection vers le profil avec un message de succès
        header('Location: profile.php?update=success');
        exit;
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
} else {
    // Rediriger vers la page de profil si la méthode n'est pas POST
    header('Location: profile.php');
    exit;
}
?>
