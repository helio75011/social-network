<?php
session_start();
require_once('config/settings.php');

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection si non connecté
    exit;
}

// Vérification si les données nécessaires sont présentes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'], $_POST['message'])) {
    $receiverId = $_POST['receiver_id'];
    $message = $_POST['message'];

    // Validation des données (vous devriez implémenter une validation plus robuste)
    if (empty($message) || !is_numeric($receiverId)) {
        // Gérer le cas où les données ne sont pas valides
        header('Location: socials.php');
        exit;
    }

    // Enregistrement du message dans la base de données
    try {
        $db = new PDO("mysql:host=" . SQL_HOST . ";dbname=" . SQL_DBNAME . ";charset=utf8", SQL_USER, SQL_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $receiverId, $message]);

    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

// Redirection vers la page de chat
header("Location: chat.php?user={$receiverId}");
exit;
