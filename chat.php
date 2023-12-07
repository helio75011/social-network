<?php
require_once('config/settings.php');

// Assurez-vous que la session est démarrée
if (!session_id()) session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection si non connecté
    exit;
}

// Récupération de l'utilisateur avec lequel l'utilisateur veut discuter
if (isset($_GET['user']) && is_numeric($_GET['user'])) {
    $otherUserId = $_GET['user'];

    // Récupération des informations de l'autre utilisateur
    try {
        $db = new PDO("mysql:host=" . SQL_HOST . ";dbname=" . SQL_DBNAME . ";charset=utf8", SQL_USER, SQL_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$otherUserId]);
        $otherUser = $stmt->fetch();

        if (!$otherUser) {
            // Gérer le cas où l'utilisateur n'est pas trouvé
            echo "Utilisateur non trouvé.";
            exit;
        }

        // Définir la variable $user_id
        $user_id = $_SESSION['user_id'];
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
} else {
    // Redirection si l'ID de l'utilisateur avec lequel discuter n'est pas fourni
    header('Location: socials.php');
    exit;
}

// Récupération de tous les messages de la conversation
try {
    $stmt = $db->prepare("
        SELECT * FROM messages
        WHERE (sender_id = :user_id AND receiver_id = :other_user_id)
        OR (sender_id = :other_user_id AND receiver_id = :user_id)
        ORDER BY timestamp
    ");

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':other_user_id', $otherUserId, PDO::PARAM_INT);
    
    $stmt->execute();
    
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Erreur lors de la récupération des messages : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat avec <?php echo htmlspecialchars($otherUser['username']); ?></title>
</head>

<body>
    <h1>Chat avec <?php echo htmlspecialchars($otherUser['username']); ?></h1>

    <style>
        #chat-messages {
            overflow-y: scroll;
            max-height: 300px;
            /* Ajustez la hauteur maximale selon vos besoins */
            padding: 0 50px 200px 50px;
        }

        .sent {
            text-align: right;
            color: blue;
            /* Couleur des messages de l'utilisateur actuel */
        }

        .received {
            text-align: left;
            color: green;
            /* Couleur des messages de l'autre utilisateur */
        }

        .message-container {
            margin: 10px;
            padding: 5px;
            border-radius: 5px;
            background-color: #f0f0f0;
            display: inline-block;
        }
    </style>
    
    <!-- Affichage de tous les messages -->
    <div id="chat-messages">
        <?php if (!empty($messages)) : ?>
            <?php foreach ($messages as $message) : ?>
                <?php
                $isSender = ($message['sender_id'] == $user_id);
                $messageClass = ($isSender) ? 'sent' : 'received';

                // Récupération du nom d'utilisateur de l'expéditeur
                $stmtSender = $db->prepare("SELECT * FROM users WHERE id = ?");
                $stmtSender->execute([$message['sender_id']]);
                $sender = $stmtSender->fetch();
                ?>

                <div class="<?php echo $messageClass; ?>">
                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                    <span><?php echo $sender['username'] . ' - ' . date('H:i', strtotime($message['timestamp'])); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun message à afficher.</p>
        <?php endif; ?>
    </div>

    <form method="post" action="send_message.php">
        <input type="hidden" name="receiver_id" value="<?php echo $otherUserId; ?>">
        <textarea name="message" placeholder="Écrire un message..." required></textarea>
        <button type="submit">Envoyer</button>
    </form>

    <a href="socials.php">Retour à la liste des utilisateurs</a>

    <script src="style/js/app.js"></script>
</body>

</html>
