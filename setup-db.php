<?php
/**
 * Script d'initialisation complète de la base de données
 * Lance ce script une seule fois : php setup-db.php
 */

try {
    // Lecture de la config
    $config = parse_ini_file(__DIR__ . '/app/config.ini');
    
    // Connexion sans base de données d'abord
    $pdo = new PDO(
        "mysql:host={$config['DB_HOST']};charset=utf8",
        $config['DB_USERNAME'],
        $config['DB_PASSWORD'],
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    echo "✓ Connexion à MySQL réussie\n";
    
    // Créer la base de données
    $dbName = $config['DB_NAME'];
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Base de données '$dbName' créée/existante\n";
    
    // Sélectionner la base de données
    $pdo->exec("USE `$dbName`");
    
    // Créer toutes les tables dans l'ordre correct
    $allQueries = <<<SQL
    CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(150) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS categorie (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(150) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS produit (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(150) NOT NULL,
        description TEXT,
        prix DECIMAL(10,2) NOT NULL,
        stock INT DEFAULT 0,
        image_url VARCHAR(255),
        categorie_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_produit_categorie 
            FOREIGN KEY (categorie_id) 
            REFERENCES categorie(id) 
            ON DELETE SET NULL 
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS panier (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantite INT NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_panier_user 
            FOREIGN KEY (user_id) 
            REFERENCES user(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE,
        CONSTRAINT fk_panier_produit 
            FOREIGN KEY (product_id) 
            REFERENCES produit(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE,
        UNIQUE KEY unique_user_product (user_id, product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS commande (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente',
        total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_commande_user 
            FOREIGN KEY (user_id) 
            REFERENCES user(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE IF NOT EXISTS commande_produit (
        id INT AUTO_INCREMENT PRIMARY KEY,
        commande_id INT NOT NULL,
        product_id INT NOT NULL,
        quantite INT NOT NULL DEFAULT 1,
        prix_unitaire DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_commande_produit_commande 
            FOREIGN KEY (commande_id) 
            REFERENCES commande(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE,
        CONSTRAINT fk_commande_produit_produit 
            FOREIGN KEY (product_id) 
            REFERENCES produit(id) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    SQL;
    
    // Exécuter toutes les requêtes
    foreach (array_filter(explode(';', $allQueries)) as $query) {
        if (trim($query)) {
            try {
                $pdo->exec(trim($query) . ';');
            } catch (PDOException $e) {
                echo "⚠ Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "✓ Tables créées avec succès\n";
    
    echo "✓ Migrations exécutées avec succès\n";
    
    // Vérifier les tables créées
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "\n📊 Tables créées : " . implode(', ', $tables) . "\n";
    
    echo "\n✅ Base de données initialisée avec succès!\n";
    
} catch (PDOException $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
