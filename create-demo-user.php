<?php
$config = parse_ini_file(__DIR__ . '/app/config.ini');
$pdo = new PDO(
    "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset=utf8",
    $config['DB_USERNAME'],
    $config['DB_PASSWORD']
);

// Créer un utilisateur de test avec l'ID 1
$stmt = $pdo->prepare("INSERT INTO user (id, nom, email, mot_de_passe) VALUES (1, 'Demo User', 'demo@example.com', ?)");
$stmt->execute([password_hash('password123', PASSWORD_DEFAULT)]);

echo "✅ Utilisateur de test créé : ID=1, email=demo@example.com, password=password123\n";
