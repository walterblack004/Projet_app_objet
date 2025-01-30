<?php
session_start();

// Connexion à la base de données avec PDO sécurisé
$dsn = 'mysql:host=localhost;dbname=Projet_app_objet;charset=utf8mb4';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, 'root', '', $options);

// Fonction de chiffrement des pseudos et mots de passe
function hasherDonnee($data) {
    return password_hash($data, PASSWORD_DEFAULT);
}

// Fonction de vérification des mots de passe
function verifierHash($data, $hash) {
    return password_verify($data, $hash);
}

// Fonction de chiffrement César améliorée
function chiffrerCesar($message, $decalage = 3) {
    $result = '';
    foreach (str_split($message) as $char) {
        if (ctype_alpha($char)) {
            $ascii = ord(ctype_upper($char) ? 'A' : 'a');
            $result .= chr((ord($char) - $ascii + $decalage) % 26 + $ascii);
        } else {
            $result .= $char;
        }
    }
    return $result;
}

// Fonction de déchiffrement César améliorée
function dechiffrerCesar($message, $decalage = 3) {
    return chiffrerCesar($message, 26 - $decalage);
}

// Envoi d'un message sécurisé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $messageChiffre = chiffrerCesar(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        $stmt = $pdo->prepare('INSERT INTO messages (sender_id, content, timestamp) VALUES (:sender_id, :content, NOW())');
        $stmt->execute([
            'sender_id' => $_SESSION['user_id'],
            'content' => $messageChiffre,
        ]);
        echo json_encode(['status' => 'success']);
        exit;
    }
}

// Récupération et déchiffrement des messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT * FROM messages ORDER BY timestamp ASC');
    $messages = $stmt->fetchAll();
    foreach ($messages as &$message) {
        $message['content'] = dechiffrerCesar($message['content']);
    }
    echo json_encode($messages);
    exit;
}