<?php
require_once('config/settings.php');
require_once('partials/head.php');
if (!session_id()) session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection si non connecté
    exit;
}

// Connexion à la base de données
try {
    $db = new PDO("mysql:host=" . SQL_HOST . ";dbname=" . SQL_DBNAME . ";charset=utf8", SQL_USER, SQL_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des informations de l'utilisateur
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        // Gérer le cas où l'utilisateur n'est pas trouvé
        echo "Utilisateur non trouvé.";
        exit;
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profil de l'Utilisateur</title>
    <!-- Inclure les liens CSS ici si nécessaire -->
</head>
<body>
    <h1>Profil de l'Utilisateur</h1>
    <p>Nom d'utilisateur: <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

    <a href="index.php">Accueil</a>
    <form method="post" action="update_profile.php">
        <h2>Modifier le Profil</h2>
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <!-- Ajouter d'autres champs si nécessaire -->

        <input type="submit" value="Mettre à jour">
    </form>

    <!-- Lien de déconnexion -->
    <a href="logout.php">Déconnexion</a>
</body>
</html>
