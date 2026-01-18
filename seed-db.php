<?php
/**
 * Script pour insérer des données de test
 */

$config = parse_ini_file(__DIR__ . '/app/config.ini');
$pdo = new PDO(
    "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset=utf8",
    $config['DB_USERNAME'],
    $config['DB_PASSWORD']
);

echo "Insertion de données de test...\n";

// Insérer des catégories
$categories = [
    ['Électronique', 'Produits électroniques et gadgets'],
    ['Vêtements', 'Vêtements et accessoires de mode'],
    ['Alimentation', 'Produits alimentaires et boissons'],
    ['Maison & Jardin', 'Articles pour la maison et le jardin']
];

foreach ($categories as [$nom, $description]) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO categorie (nom, description) VALUES (?, ?)");
    $stmt->execute([$nom, $description]);
}

echo "✓ Catégories insérées\n";

// Insérer des utilisateurs
$users = [
    ['Alice', 'alice@example.com', password_hash('password123', PASSWORD_DEFAULT)],
    ['Bob', 'bob@example.com', password_hash('password123', PASSWORD_DEFAULT)],
    ['Charlie', 'charlie@example.com', password_hash('password123', PASSWORD_DEFAULT)]
];

foreach ($users as [$nom, $email, $password]) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO user (nom, email, mot_de_passe) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $password]);
}

echo "✓ Utilisateurs insérés\n";

// Insérer des produits
$products = [
    ['Laptop Gaming', 'Ordinateur portable haute performance avec processeur dernière génération', 1299.99, 5, 'https://via.placeholder.com/400?text=Laptop+Gaming', 1],
    ['Souris Wireless', 'Souris sans fil ergonomique avec batterie longue durée', 29.99, 15, 'https://via.placeholder.com/400?text=Souris', 1],
    ['Clavier Mécanique', 'Clavier mécanique RGB avec switchs Cherry MX', 129.99, 8, 'https://via.placeholder.com/400?text=Clavier', 1],
    ['T-Shirt Coton', 'T-shirt 100% coton confortable et durable', 19.99, 20, 'https://via.placeholder.com/400?text=T-Shirt', 2],
    ['Jeans Slim', 'Jeans slim fit de haute qualité', 59.99, 12, 'https://via.placeholder.com/400?text=Jeans', 2],
    ['Café Premium', 'Café en grains torréfaction moyenne, 500g', 12.99, 30, 'https://via.placeholder.com/400?text=Café', 3],
    ['Chocolat Noir', 'Chocolat noir 70% cacao, tablette 100g', 5.99, 50, 'https://via.placeholder.com/400?text=Chocolat', 3],
    ['Plante Verte', 'Monstera deliciosa, hauteur environ 40cm', 34.99, 7, 'https://via.placeholder.com/400?text=Plante', 4],
    ['Lampe LED', 'Lampe LED de bureau avec variateur de luminosité', 44.99, 10, 'https://via.placeholder.com/400?text=Lampe', 4],
    ['Étagère Murale', 'Étagère murale en bois, 80x20cm', 49.99, 6, 'https://via.placeholder.com/400?text=Étagère', 4]
];

foreach ($products as [$nom, $description, $prix, $stock, $image, $categorie_id]) {
    $stmt = $pdo->prepare("INSERT INTO produit (nom, description, prix, stock, image_url, categorie_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $description, $prix, $stock, $image, $categorie_id]);
}

echo "✓ Produits insérés\n";

echo "\n✅ Données de test insérées avec succès!\n";
echo "Utilisateur de test : email=alice@example.com, password=password123\n";
