<?php

namespace Mini\Models;

use Mini\Core\Database;
use Mini\Models\Cart;
use Mini\Models\Product;
use PDO;

class Order
{
    private $id;
    private $user_id;
    private $statut;
    private $total;
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

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
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
     * Récupère toutes les commandes d'un utilisateur
     * @param int $user_id
     * @return array
     */
    public static function getByUserId($user_id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM commande 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les commandes validées
     * @return array
     */
    public static function getValidatedOrders()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("
            SELECT c.*, u.nom as user_nom, u.email as user_email
            FROM commande c
            INNER JOIN user u ON c.user_id = u.id
            WHERE c.statut = 'validee'
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une commande par son ID avec ses produits
     * @param int $id
     * @return array|null
     */
    public static function findByIdWithProducts($id)
    {
        $pdo = Database::getPDO();
        
        // Récupère la commande
        $stmt = $pdo->prepare("
            SELECT c.*, u.nom as user_nom, u.email as user_email
            FROM commande c
            INNER JOIN user u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            return null;
        }
        
        // Récupère les produits de la commande
        $stmt = $pdo->prepare("
            SELECT cp.*, p.nom as product_nom, p.image_url, cat.nom as categorie_nom
            FROM commande_produit cp
            INNER JOIN produit p ON cp.product_id = p.id
            LEFT JOIN categorie cat ON p.categorie_id = cat.id
            WHERE cp.commande_id = ?
        ");
        $stmt->execute([$id]);
        $order['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $order;
    }

    /**
     * Crée une nouvelle commande à partir du panier
     * @param int $user_id
     * @return int|false L'ID de la commande créée ou false en cas d'erreur
     */
    public static function createFromCart($user_id)
    {
        $pdo = Database::getPDO();
        
        // Récupère les articles du panier
        $cartItems = Cart::getByUserId($user_id);
        
        if (empty($cartItems)) {
            return false;
        }
        
        // Calcule le total
        $total = Cart::getTotalByUserId($user_id);
        
        try {
            $pdo->beginTransaction();
            
            // Crée la commande
            $stmt = $pdo->prepare("INSERT INTO commande (user_id, statut, total) VALUES (?, 'validee', ?)");
            $stmt->execute([$user_id, $total]);
            $orderId = $pdo->lastInsertId();
            
            // Ajoute les produits à la commande
            $stmt = $pdo->prepare("INSERT INTO commande_produit (commande_id, product_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
            
            foreach ($cartItems as $item) {
                $product = Product::findById($item['id']);
                if ($product) {
                    $stmt->execute([
                        $orderId,
                        $item['id'],
                        $item['quantite'],
                        $product['prix']
                    ]);
                    
                    // Met à jour le stock
                    $newStock = $product['stock'] - $item['quantite'];
                    $updateStmt = $pdo->prepare("UPDATE produit SET stock = ? WHERE id = ?");
                    $updateStmt->execute([$newStock, $item['id']]);
                }
            }
            
            // Vide le panier
            Cart::clearByUserId($user_id);
            
            $pdo->commit();
            return $orderId;
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    /**
     * Met à jour le statut d'une commande
     * @return bool
     */
    public function update()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE commande SET statut = ?, total = ? WHERE id = ?");
        return $stmt->execute([$this->statut, $this->total, $this->id]);
    }

    /**
     * Supprime une commande
     * @return bool
     */
    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM commande WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

