<?php
$config = parse_ini_file(__DIR__ . '/app/config.ini');
$pdo = new PDO('mysql:host=' . $config['DB_HOST'] . ';charset=utf8', $config['DB_USERNAME'], $config['DB_PASSWORD']);

try {
    $pdo->exec('DROP DATABASE IF EXISTS ' . $config['DB_NAME']);
    echo "Base de données supprimée\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
