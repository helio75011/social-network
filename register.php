<?php
include 'config/settings.php';

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    try {
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            $messages[] = "Inscription réussie !";
        } else {
            $messages[] = "Erreur lors de l'inscription.";
        }
    } catch (PDOException $e) {
        $messages[] = "Erreur de base de données : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
    <form method="post">
        Nom d'utilisateur: <input type="text" name="username" required><br>
        Email: <input type="email" name="email" required><br>
        Mot de passe: <input type="password" name="password" required><br>
        <input type="submit" value="S'inscrire">
    </form>
    <?php foreach ($messages as $message) {
        echo $message . "<br>";
    } ?>
    <a href="login.php">connexion</a>
</body>
</html>
