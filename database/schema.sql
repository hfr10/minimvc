-- ============================================================
-- Script de création de la base de données Mini MVC
-- ============================================================
-- Créé pour EFREI B2 - Mini projet pédagogique MVC

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS `mini_mvc` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mini_mvc`;

-- ============================================================
-- Table: user
-- Description: Utilisateurs de l'application
-- ============================================================
CREATE TABLE IF NOT EXISTS `user` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: categorie
-- Description: Catégories de produits
-- ============================================================
CREATE TABLE IF NOT EXISTS `categorie` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: produit
-- Description: Produits disponibles à la vente
-- ============================================================
CREATE TABLE IF NOT EXISTS `produit` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `description` TEXT,
    `prix` DECIMAL(10,2) NOT NULL,
    `stock` INT DEFAULT 0,
    `image_url` VARCHAR(255),
    `categorie_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_produit_categorie` 
        FOREIGN KEY (`categorie_id`) 
        REFERENCES `categorie`(`id`) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: panier
-- Description: Panier d'achat des utilisateurs
-- ============================================================
CREATE TABLE IF NOT EXISTS `panier` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantite` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_panier_user` 
        FOREIGN KEY (`user_id`) 
        REFERENCES `user`(`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT `fk_panier_produit` 
        FOREIGN KEY (`product_id`) 
        REFERENCES `produit`(`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    UNIQUE KEY `unique_user_product` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: commande
-- Description: Commandes des utilisateurs
-- ============================================================
CREATE TABLE IF NOT EXISTS `commande` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `statut` ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente',
    `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_commande_user` 
        FOREIGN KEY (`user_id`) 
        REFERENCES `user`(`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: commande_produit
-- Description: Relation many-to-many entre commandes et produits
-- ============================================================
CREATE TABLE IF NOT EXISTS `commande_produit` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `commande_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantite` INT NOT NULL DEFAULT 1,
    `prix_unitaire` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_commande_produit_commande` 
        FOREIGN KEY (`commande_id`) 
        REFERENCES `commande`(`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT `fk_commande_produit_produit` 
        FOREIGN KEY (`product_id`) 
        REFERENCES `produit`(`id`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Données d'exemple (optionnel)
-- ============================================================

-- Insérer quelques catégories
INSERT IGNORE INTO `categorie` (`nom`, `description`) VALUES
('Électronique', 'Produits électroniques et gadgets'),
('Vêtements', 'Vêtements et accessoires de mode'),
('Alimentation', 'Produits alimentaires et boissons'),
('Maison & Jardin', 'Articles pour la maison et le jardin');

-- Insérer un utilisateur de test
-- Email: demo@example.com
-- Mot de passe: password123 (hasé avec PASSWORD_DEFAULT)
INSERT IGNORE INTO `user` (`id`, `nom`, `email`, `mot_de_passe`) VALUES
(1, 'Utilisateur Demo', 'demo@example.com', '$2y$10$YIjlrVyY6LMFz/lp9zFCnOqjCLs6BPyFkWDIU0T4cPmRXHrwDKDji');

-- ============================================================
-- Fin du script
-- ============================================================
