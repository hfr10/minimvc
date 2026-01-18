-- Migration pour ajouter les tables catégorie, panier, commande et commande_produit
-- Exécutez ce script dans votre base de données MySQL/MariaDB

-- 1. Création de la table catégorie
CREATE TABLE IF NOT EXISTS categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Modification de la table produit pour ajouter la clé étrangère vers catégorie
-- NOTE: Si la colonne existe déjà, cette commande échouera. 
-- Dans ce cas, exécutez seulement la partie ADD CONSTRAINT (en commentant ADD COLUMN)
-- Vérifiez d'abord si la colonne existe avec: SHOW COLUMNS FROM produit LIKE 'categorie_id';

-- Étape 1: Ajouter la colonne (si elle n'existe pas déjà)
ALTER TABLE produit 
ADD COLUMN categorie_id INT NULL;

-- Étape 2: Ajouter la contrainte de clé étrangère (si elle n'existe pas déjà)
-- Si la contrainte existe déjà, cette commande échouera - c'est normal
ALTER TABLE produit 
ADD CONSTRAINT fk_produit_categorie 
    FOREIGN KEY (categorie_id) 
    REFERENCES categorie(id) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE;

-- 3. Création de la table panier
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

-- 4. Création de la table commande
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

-- 5. Création de la table commande_produit (table de liaison)
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

-- Insertion de quelques catégories d'exemple
INSERT INTO categorie (nom, description) VALUES
('Électronique', 'Produits électroniques et gadgets'),
('Vêtements', 'Vêtements et accessoires de mode'),
('Alimentation', 'Produits alimentaires et boissons'),
('Maison & Jardin', 'Articles pour la maison et le jardin')
ON DUPLICATE KEY UPDATE nom=nom;

