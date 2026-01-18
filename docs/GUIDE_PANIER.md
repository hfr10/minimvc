# Guide : Mise en place du système de panier

Ce guide vous explique étape par étape comment mettre en place le système de panier dans votre application Mini MVC.

## 📋 Table des matières

1. [Prérequis](#prérequis)
2. [Étape 1 : Base de données](#étape-1--base-de-données)
3. [Étape 2 : Modèle Cart](#étape-2--modèle-cart)
4. [Étape 3 : Contrôleur CartController](#étape-3--contrôleur-cartcontroller)
5. [Étape 4 : Routes](#étape-4--routes)
6. [Étape 5 : Vues](#étape-5--vues)
7. [Utilisation](#utilisation)
8. [Exemples pratiques](#exemples-pratiques)

---

## Prérequis

Avant de commencer, assurez-vous d'avoir :
- ✅ Une base de données MySQL/MariaDB configurée
- ✅ Les tables `user` et `produit` déjà créées
- ✅ Le framework Mini MVC fonctionnel
- ✅ Les modèles `User` et `Product` existants

---

## Étape 1 : Base de données

### 1.1 Créer la table `panier`

Exécutez cette requête SQL dans votre base de données :

```sql
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
```

### 1.2 Explication de la structure

- **`id`** : Identifiant unique de l'article dans le panier
- **`user_id`** : Référence à l'utilisateur propriétaire du panier
- **`product_id`** : Référence au produit ajouté
- **`quantite`** : Quantité du produit (par défaut 1)
- **`created_at`** / **`updated_at`** : Timestamps automatiques
- **Contrainte unique** : Un utilisateur ne peut avoir qu'un seul article par produit (la quantité sera mise à jour si le produit est déjà présent)

---

## Étape 2 : Modèle Cart

### 2.1 Créer le fichier `app/Models/Cart.php`

```php
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

    // Getters / Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    
    public function getProductId() { return $this->product_id; }
    public function setProductId($product_id) { $this->product_id = $product_id; }
    
    public function getQuantite() { return $this->quantite; }
    public function setQuantite($quantite) { $this->quantite = $quantite; }

    // Méthodes principales
    
    /**
     * Récupère tous les articles du panier d'un utilisateur
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
     * Calcule le total du panier
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
     */
    public function save()
    {
        $pdo = Database::getPDO();
        
        // Vérifie si l'article existe déjà
        $existing = self::findByUserAndProduct($this->user_id, $this->product_id);
        
        if ($existing) {
            // Met à jour la quantité
            $stmt = $pdo->prepare("UPDATE panier SET quantite = ? WHERE user_id = ? AND product_id = ?");
            return $stmt->execute([$this->quantite, $this->user_id, $this->product_id]);
        } else {
            // Ajoute un nouvel article
            $stmt = $pdo->prepare("INSERT INTO panier (user_id, product_id, quantite) VALUES (?, ?, ?)");
            return $stmt->execute([$this->user_id, $this->product_id, $this->quantite]);
        }
    }

    /**
     * Supprime un article du panier
     */
    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM panier WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    /**
     * Vide le panier d'un utilisateur
     */
    public static function clearByUserId($user_id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM panier WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    /**
     * Trouve un article spécifique dans le panier
     */
    public static function findByUserAndProduct($user_id, $product_id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM panier WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
```

---

## Étape 3 : Contrôleur CartController

### 3.1 Créer le fichier `app/Controllers/CartController.php`

```php
<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Cart;
use Mini\Models\Product;

final class CartController extends Controller
{
    /**
     * Affiche le panier d'un utilisateur
     */
    public function show(): void
    {
        $user_id = $_GET['user_id'] ?? 1; // Par défaut user_id = 1
        
        $cartItems = Cart::getByUserId($user_id);
        $total = Cart::getTotalByUserId($user_id);
        
        $this->render('cart/index', params: [
            'title' => 'Mon panier',
            'cartItems' => $cartItems,
            'total' => $total,
            'user_id' => $user_id
        ]);
    }

    /**
     * Ajoute un produit au panier depuis un formulaire HTML
     */
    public function addFromForm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products');
            return;
        }
        
        $product_id = $_POST['product_id'] ?? null;
        $quantite = intval($_POST['quantite'] ?? 1);
        $user_id = $_POST['user_id'] ?? 1;
        
        if (!$product_id) {
            header('Location: /products');
            return;
        }
        
        // Vérifie que le produit existe et le stock
        $product = Product::findById($product_id);
        if (!$product || $product['stock'] < $quantite) {
            header('Location: /products/show?id=' . $product_id . '&error=stock_insuffisant');
            return;
        }
        
        $cart = new Cart();
        $cart->setUserId($user_id);
        $cart->setProductId($product_id);
        $cart->setQuantite($quantite);
        
        if ($cart->save()) {
            header('Location: /cart?user_id=' . $user_id . '&success=added');
        } else {
            header('Location: /products/show?id=' . $product_id . '&error=add_failed');
        }
    }

    /**
     * Met à jour la quantité d'un produit
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        $cart_id = $_POST['cart_id'] ?? null;
        $quantite = intval($_POST['quantite'] ?? 1);
        
        if (!$cart_id) {
            header('Location: /cart?user_id=' . ($_GET['user_id'] ?? 1) . '&error=missing_fields');
            return;
        }
        
        // Récupère l'article et vérifie le stock
        $pdo = \Mini\Core\Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM panier WHERE id = ?");
        $stmt->execute([$cart_id]);
        $cartItem = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$cartItem) {
            header('Location: /cart?user_id=' . ($_GET['user_id'] ?? 1) . '&error=item_not_found');
            return;
        }
        
        $product = Product::findById($cartItem['product_id']);
        if ($product['stock'] < $quantite) {
            header('Location: /cart?user_id=' . $cartItem['user_id'] . '&error=stock_insuffisant');
            return;
        }
        
        $cart = new Cart();
        $cart->setId($cart_id);
        $cart->setUserId($cartItem['user_id']);
        $cart->setProductId($cartItem['product_id']);
        $cart->setQuantite($quantite);
        
        if ($cart->save()) {
            header('Location: /cart?user_id=' . $cartItem['user_id'] . '&success=updated');
        } else {
            header('Location: /cart?user_id=' . $cartItem['user_id'] . '&error=update_failed');
        }
    }

    /**
     * Supprime un article du panier
     */
    public function remove(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        $cart_id = $_POST['cart_id'] ?? null;
        
        if (!$cart_id) {
            header('Location: /cart?user_id=' . ($_GET['user_id'] ?? 1) . '&error=missing_cart_id');
            return;
        }
        
        // Récupère le user_id avant suppression
        $pdo = \Mini\Core\Database::getPDO();
        $stmt = $pdo->prepare("SELECT user_id FROM panier WHERE id = ?");
        $stmt->execute([$cart_id]);
        $cartItem = $stmt->fetch(\PDO::FETCH_ASSOC);
        $user_id = $cartItem['user_id'] ?? ($_GET['user_id'] ?? 1);
        
        $cart = new Cart();
        $cart->setId($cart_id);
        
        if ($cart->delete()) {
            header('Location: /cart?user_id=' . $user_id . '&success=removed');
        } else {
            header('Location: /cart?user_id=' . $user_id . '&error=delete_failed');
        }
    }

    /**
     * Vide le panier
     */
    public function clear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        $user_id = $_POST['user_id'] ?? $_GET['user_id'] ?? 1;
        
        if (Cart::clearByUserId($user_id)) {
            header('Location: /cart?user_id=' . $user_id . '&success=cleared');
        } else {
            header('Location: /cart?user_id=' . $user_id . '&error=clear_failed');
        }
    }
}
```

---

## Étape 4 : Routes

### 4.1 Ajouter les routes dans `public/index.php`

```php
$routes = [
    // ... vos routes existantes ...
    
    // Routes pour le panier
    ['GET', '/cart', [Mini\Controllers\CartController::class, 'show']],
    ['POST', '/cart/add-from-form', [Mini\Controllers\CartController::class, 'addFromForm']],
    ['POST', '/cart/update', [Mini\Controllers\CartController::class, 'update']],
    ['POST', '/cart/remove', [Mini\Controllers\CartController::class, 'remove']],
    ['POST', '/cart/clear', [Mini\Controllers\CartController::class, 'clear']],
];
```

---

## Étape 5 : Vues

### 5.1 Créer le dossier `app/Views/cart/`

### 5.2 Créer `app/Views/cart/index.php`

```php
<!-- Vue du panier -->
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h2>Mon panier</h2>
    
    <?php if (empty($cartItems)): ?>
        <p>Votre panier est vide.</p>
        <a href="/products">Voir les produits</a>
    <?php else: ?>
        <!-- Liste des articles -->
        <?php foreach ($cartItems as $item): ?>
            <div>
                <h3><?= htmlspecialchars($item['nom']) ?></h3>
                <p>Prix : <?= number_format($item['prix'], 2, ',', ' ') ?> €</p>
                <p>Quantité : <?= htmlspecialchars($item['quantite']) ?></p>
                
                <!-- Formulaire pour mettre à jour la quantité -->
                <form method="POST" action="/cart/update">
                    <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['panier_id']) ?>">
                    <input type="number" name="quantite" value="<?= htmlspecialchars($item['quantite']) ?>" min="1">
                    <button type="submit">Mettre à jour</button>
                </form>
                
                <!-- Formulaire pour supprimer -->
                <form method="POST" action="/cart/remove">
                    <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['panier_id']) ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
        
        <!-- Total -->
        <div>
            <strong>Total : <?= number_format($total, 2, ',', ' ') ?> €</strong>
        </div>
        
        <!-- Vider le panier -->
        <form method="POST" action="/cart/clear">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
            <button type="submit">Vider le panier</button>
        </form>
    <?php endif; ?>
</div>
```

### 5.3 Ajouter un bouton "Ajouter au panier" dans la liste des produits

Dans `app/Views/product/list-products.php` :

```php
<?php foreach ($products as $product): ?>
    <div>
        <h3><?= htmlspecialchars($product['nom']) ?></h3>
        <p><?= number_format($product['prix'], 2, ',', ' ') ?> €</p>
        
        <!-- Formulaire pour ajouter au panier -->
        <form method="POST" action="/cart/add-from-form">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
            <input type="hidden" name="quantite" value="1">
            <input type="hidden" name="user_id" value="1">
            <button type="submit" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                🛒 Ajouter au panier
            </button>
        </form>
    </div>
<?php endforeach; ?>
```

---

## Utilisation

### Ajouter un produit au panier

1. **Depuis un formulaire HTML** :
```html
<form method="POST" action="/cart/add-from-form">
    <input type="hidden" name="product_id" value="5">
    <input type="hidden" name="quantite" value="2">
    <input type="hidden" name="user_id" value="1">
    <button type="submit">Ajouter au panier</button>
</form>
```

2. **Via le modèle directement** :
```php
$cart = new Cart();
$cart->setUserId(1);
$cart->setProductId(5);
$cart->setQuantite(2);
$cart->save();
```

### Récupérer le panier d'un utilisateur

```php
$cartItems = Cart::getByUserId(1);
$total = Cart::getTotalByUserId(1);
```

### Mettre à jour la quantité

```php
$cart = new Cart();
$cart->setId($cart_id);
$cart->setUserId(1);
$cart->setProductId(5);
$cart->setQuantite(3);
$cart->save(); // Met à jour si existe, crée si n'existe pas
```

### Supprimer un article

```php
$cart = new Cart();
$cart->setId($cart_id);
$cart->delete();
```

### Vider le panier

```php
Cart::clearByUserId(1);
```

---

## Exemples pratiques

### Exemple 1 : Page de détail produit avec ajout au panier

```php
<!-- app/Views/product/show.php -->
<form method="POST" action="/cart/add-from-form">
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <input type="hidden" name="user_id" value="1">
    <label>Quantité :</label>
    <input type="number" name="quantite" value="1" min="1" max="<?= $product['stock'] ?>">
    <button type="submit">Ajouter au panier</button>
</form>
```

### Exemple 2 : Afficher le nombre d'articles dans le panier

```php
// Dans votre layout ou header
<?php
$cartCount = 0;
if (isset($_GET['user_id'])) {
    $cartItems = Cart::getByUserId($_GET['user_id']);
    $cartCount = count($cartItems);
}
?>
<a href="/cart?user_id=1">
    🛒 Panier (<?= $cartCount ?>)
</a>
```

### Exemple 3 : Vérifier si un produit est déjà dans le panier

```php
$existing = Cart::findByUserAndProduct($user_id, $product_id);
if ($existing) {
    echo "Déjà dans le panier (quantité : " . $existing['quantite'] . ")";
} else {
    echo "Ajouter au panier";
}
```

---

## Points importants à retenir

1. **Contrainte unique** : Un utilisateur ne peut avoir qu'un seul article par produit. Si vous ajoutez le même produit, la quantité sera mise à jour.

2. **Vérification du stock** : Toujours vérifier le stock disponible avant d'ajouter ou de mettre à jour.

3. **user_id** : Pour l'instant, on utilise `user_id=1` par défaut. Dans une vraie application, vous devriez utiliser une session ou un système d'authentification.

4. **Sécurité** : Validez toujours les données entrantes et vérifiez les permissions.

5. **Performance** : Pour de gros volumes, pensez à ajouter des index sur `user_id` et `product_id`.

---

## Dépannage

### Le produit ne s'ajoute pas au panier
- Vérifiez que la table `panier` existe
- Vérifiez que les clés étrangères sont correctes
- Vérifiez les logs d'erreur PHP

### La quantité ne se met pas à jour
- Vérifiez que la contrainte unique fonctionne
- Vérifiez que `findByUserAndProduct` retourne bien un résultat

### Erreur de clé étrangère
- Assurez-vous que les tables `user` et `produit` existent
- Vérifiez que les IDs utilisés existent dans ces tables

---

## Prochaines étapes

Une fois le panier fonctionnel, vous pouvez :
- ✅ Créer un système de commandes (voir `docs/PANIER_COMMANDES.md`)
- ✅ Ajouter un système de session pour gérer l'utilisateur connecté
- ✅ Implémenter un système de cookies pour le panier des visiteurs
- ✅ Ajouter des notifications en temps réel
- ✅ Créer une API REST pour le panier

---

**Besoin d'aide ?** Consultez les autres fichiers de documentation dans le dossier `docs/`.

