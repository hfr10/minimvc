<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class Cart
{
    private $id;
    private $user_id;
    private $product_id;
    private $quantite;
    private $created_at;
    private $updated_at;

    // =====================
    // Getters / Setters
    // =====================

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    // =====================
    // Méthodes CRUD
    // =====================

    /**
     * Récupère tous les articles du panier d'un utilisateur
     * @param int $user_id
     * @return array
     */
    public static function getByUserId($user_id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT p.*, c.quantite, c.id as panier_id, cat.nom as categorie_nom
            FROM panier c
            INNER JOIN produit p ON c.product_id = p.id
            LEFT JOIN categorie cat ON p.categorie_id = cat.id
            WHERE c.user_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un article du panier par user_id et product_id
     * @param int $user_id
     * @param int $product_id
     * @return array|null
     */
    public static function findByUserAndProduct($user_id, $product_id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM panier WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le total du panier d'un utilisateur
     * @param int $user_id
     * @return float
     */
    public static function getTotalByUserId($user_id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT SUM(p.prix * c.quantite) as total
            FROM panier c
            INNER JOIN produit p ON c.product_id = p.id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0.00;
    }

    /**
     * Ajoute ou met à jour un produit dans le panier
     * @return bool
     */
    public function save()
    {
        $pdo = Database::getPDO();
        
        // Vérifie si l'article existe déjà dans le panier
        $existing = self::findByUserAndProduct($this->user_id, $this->product_id);
        
        if ($existing) {
            // Ajoute à la quantité existante
            $stmt = $pdo->prepare("UPDATE panier SET quantite = quantite + ? WHERE user_id = ? AND product_id = ?");
            return $stmt->execute([$this->quantite, $this->user_id, $this->product_id]);
        } else {
            // Ajoute un nouvel article
            $stmt = $pdo->prepare("INSERT INTO panier (user_id, product_id, quantite) VALUES (?, ?, ?)");
            return $stmt->execute([$this->user_id, $this->product_id, $this->quantite]);
        }
    }

    /**
     * Supprime un article du panier
     * @return bool
     */
    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM panier WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    /**
     * Vide le panier d'un utilisateur
     * @param int $user_id
     * @return bool
     */
    public static function clearByUserId($user_id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM panier WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }
}

