# Guide : Système de Panier et Commandes

Ce document explique le système de panier et de commandes implémenté dans le framework Mini MVC.

## Structure de la base de données

### Tables créées

1. **categorie** : Stocke les catégories de produits
   - `id` : Identifiant unique
   - `nom` : Nom de la catégorie
   - `description` : Description de la catégorie
   - `created_at`, `updated_at` : Timestamps

2. **panier** : Stocke les produits dans le panier de chaque utilisateur
   - `id` : Identifiant unique
   - `user_id` : Référence à l'utilisateur
   - `product_id` : Référence au produit
   - `quantite` : Quantité du produit dans le panier
   - `created_at`, `updated_at` : Timestamps
   - Contrainte unique : un utilisateur ne peut avoir qu'un seul article par produit dans son panier

3. **commande** : Stocke les commandes validées
   - `id` : Identifiant unique
   - `user_id` : Référence à l'utilisateur
   - `statut` : Statut de la commande (en_attente, validee, annulee)
   - `total` : Montant total de la commande
   - `created_at`, `updated_at` : Timestamps

4. **commande_produit** : Table de liaison entre commandes et produits
   - `id` : Identifiant unique
   - `commande_id` : Référence à la commande
   - `product_id` : Référence au produit
   - `quantite` : Quantité commandée
   - `prix_unitaire` : Prix unitaire au moment de la commande
   - `created_at` : Timestamp

### Modification de la table produit

La table `produit` a été modifiée pour inclure :
- `categorie_id` : Référence à la catégorie (clé étrangère, peut être NULL)

## Installation

1. Exécutez le script SQL de migration :
```sql
-- Exécutez le fichier database/migrations.sql dans votre base de données
```

## Modèles créés

### Category (`app/Models/Category.php`)
Gère les catégories de produits.

**Méthodes principales :**
- `getAll()` : Récupère toutes les catégories
- `findById($id)` : Récupère une catégorie par son ID
- `save()` : Crée une nouvelle catégorie
- `update()` : Met à jour une catégorie
- `delete()` : Supprime une catégorie

### Cart (`app/Models/Cart.php`)
Gère le panier d'achat des utilisateurs.

**Méthodes principales :**
- `getByUserId($user_id)` : Récupère tous les articles du panier d'un utilisateur
- `findByUserAndProduct($user_id, $product_id)` : Trouve un article spécifique
- `getTotalByUserId($user_id)` : Calcule le total du panier
- `save()` : Ajoute ou met à jour un produit dans le panier
- `delete()` : Supprime un article du panier
- `clearByUserId($user_id)` : Vide le panier d'un utilisateur

### Order (`app/Models/Order.php`)
Gère les commandes.

**Méthodes principales :**
- `getByUserId($user_id)` : Récupère toutes les commandes d'un utilisateur
- `getValidatedOrders()` : Récupère toutes les commandes validées
- `findByIdWithProducts($id)` : Récupère une commande avec ses produits
- `createFromCart($user_id)` : Crée une commande à partir du panier (valide automatiquement)
- `update()` : Met à jour le statut d'une commande
- `delete()` : Supprime une commande

## Contrôleurs créés

### CartController (`app/Controllers/CartController.php`)

**Routes disponibles :**
- `GET /cart?user_id=X` : Affiche le panier d'un utilisateur
- `POST /cart/add` : Ajoute un produit au panier
  ```json
  {
    "user_id": 1,
    "product_id": 2,
    "quantite": 3
  }
  ```
- `POST /cart/update` : Met à jour la quantité d'un produit
  ```json
  {
    "cart_id": 1,
    "quantite": 5
  }
  ```
- `POST /cart/remove` : Supprime un article du panier
  ```json
  {
    "cart_id": 1
  }
  ```
- `POST /cart/clear` : Vide le panier
  ```json
  {
    "user_id": 1
  }
  ```

### OrderController (`app/Controllers/OrderController.php`)

**Routes disponibles :**
- `GET /orders?user_id=X` : Liste les commandes d'un utilisateur
- `GET /orders/validated` : Liste toutes les commandes validées
- `GET /orders/show?id=X` : Affiche les détails d'une commande
- `POST /orders/create` : Crée une commande à partir du panier
  ```json
  {
    "user_id": 1
  }
  ```
- `POST /orders/update-status` : Met à jour le statut d'une commande
  ```json
  {
    "order_id": 1,
    "statut": "validee"
  }
  ```

## Utilisation

### Ajouter un produit au panier

```php
// Via API
POST /cart/add
{
    "user_id": 1,
    "product_id": 5,
    "quantite": 2
}
```

### Créer une commande

```php
// Via API
POST /orders/create
{
    "user_id": 1
}
```

Cette action :
1. Récupère tous les articles du panier
2. Calcule le total
3. Crée la commande avec le statut "validee"
4. Enregistre chaque produit dans `commande_produit` avec le prix au moment de la commande
5. Met à jour le stock des produits
6. Vide le panier

### Récupérer les commandes d'un utilisateur

```php
// Via API
GET /orders?user_id=1
```

## Notes importantes

1. **Stock** : Lors de la création d'une commande, le stock est automatiquement déduit. Assurez-vous que le stock est suffisant avant de créer la commande.

2. **Prix** : Le prix unitaire est enregistré dans `commande_produit` au moment de la commande, permettant de conserver l'historique même si le prix change ensuite.

3. **Statuts de commande** : Les statuts possibles sont :
   - `en_attente` : Commande en attente
   - `validee` : Commande validée
   - `annulee` : Commande annulée

4. **Catégories** : Les produits peuvent être associés à une catégorie lors de leur création. Le formulaire de création de produit inclut maintenant un champ de sélection de catégorie.

## Exemples d'utilisation

### Workflow complet

1. **Créer des catégories** (via SQL ou interface admin)
2. **Créer des produits** avec leurs catégories
3. **Ajouter des produits au panier** d'un utilisateur
4. **Vérifier le panier** de l'utilisateur
5. **Créer une commande** à partir du panier
6. **Consulter les commandes** de l'utilisateur

### Exemple de requête SQL pour créer une catégorie

```sql
INSERT INTO categorie (nom, description) 
VALUES ('Électronique', 'Produits électroniques et gadgets');
```

### Exemple de requête pour voir le panier d'un utilisateur

```sql
SELECT p.nom, p.prix, c.quantite, (p.prix * c.quantite) as sous_total
FROM panier c
INNER JOIN produit p ON c.product_id = p.id
WHERE c.user_id = 1;
```

