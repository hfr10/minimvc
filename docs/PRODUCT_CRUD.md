# Guide de création : Gestion des Produits (CRUD)

Ce document explique en détail comment créer un système complet de gestion de produits dans le framework Mini MVC, de la création de la table de base de données jusqu'aux vues.

## Table des matières

1. [Création de la table `produit`](#1-création-de-la-table-produit)
2. [Création du modèle `Product`](#2-création-du-modèle-product)
3. [Création du contrôleur `ProductController`](#3-création-du-contrôleur-productcontroller)
4. [Création des vues](#4-création-des-vues)
5. [Configuration des routes](#5-configuration-des-routes)
6. [Résumé et structure finale](#6-résumé-et-structure-finale)

---

## 1. Création de la table `produit`

### 1.1 Structure de la table

La table `produit` stocke les informations des produits. Voici la structure SQL :

```sql
CREATE TABLE produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    image_url VARCHAR(255)
);
```

### 1.2 Description des colonnes

- **`id`** : Identifiant unique auto-incrémenté (clé primaire)
- **`nom`** : Nom du produit (obligatoire, max 150 caractères)
- **`description`** : Description détaillée du produit (optionnel, type TEXT)
- **`prix`** : Prix du produit (obligatoire, décimal avec 2 décimales)
- **`stock`** : Quantité en stock (obligatoire, entier)
- **`image_url`** : URL de l'image du produit (optionnel)

### 1.3 Exécution de la requête

Exécutez cette requête SQL dans votre base de données MySQL/MariaDB pour créer la table.

---

## 2. Création du modèle `Product`

### 2.1 Fichier : `app/Models/Product.php`

Le modèle `Product` représente l'entité produit et gère toutes les interactions avec la base de données.

#### 2.1.1 Structure de base

```php
<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class Product
{
    // Propriétés privées
    private $id;
    private $nom;
    private $description;
    private $prix;
    private $stock;
    private $image_url;
    
    // Getters/Setters
    // Méthodes CRUD
}
```

#### 2.1.2 Propriétés privées

Les propriétés correspondent aux colonnes de la table :

```php
private $id;
private $nom;
private $description;
private $prix;
private $stock;
private $image_url;
```

#### 2.1.3 Getters et Setters

Pour chaque propriété, on crée un getter et un setter :

```php
// Exemple pour 'nom'
public function getNom()
{
    return $this->nom;
}

public function setNom($nom)
{
    $this->nom = $nom;
}
```

**Tous les getters/setters nécessaires :**
- `getId()` / `setId($id)`
- `getNom()` / `setNom($nom)`
- `getDescription()` / `setDescription($description)`
- `getPrix()` / `setPrix($prix)`
- `getStock()` / `setStock($stock)`
- `getImageUrl()` / `setImageUrl($image_url)`

#### 2.1.4 Méthodes CRUD

##### `getAll()` - Récupérer tous les produits

```php
public static function getAll()
{
    $pdo = Database::getPDO();
    $stmt = $pdo->query("SELECT * FROM produit ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

**Fonctionnalités :**
- Récupère tous les produits de la base
- Trie par ID décroissant (plus récents en premier)
- Retourne un tableau associatif

##### `findById($id)` - Récupérer un produit par ID

```php
public static function findById($id)
{
    $pdo = Database::getPDO();
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

**Fonctionnalités :**
- Utilise une requête préparée (sécurité)
- Retourne un produit ou `null` si non trouvé

##### `save()` - Créer un nouveau produit

```php
public function save()
{
    $pdo = Database::getPDO();
    $stmt = $pdo->prepare("INSERT INTO produit (nom, description, prix, stock, image_url) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $this->nom,
        $this->description,
        $this->prix,
        $this->stock,
        $this->image_url
    ]);
}
```

**Fonctionnalités :**
- Insère un nouveau produit dans la base
- Utilise des requêtes préparées (protection contre les injections SQL)
- Retourne `true` en cas de succès, `false` sinon

##### `update()` - Mettre à jour un produit

```php
public function update()
{
    $pdo = Database::getPDO();
    $stmt = $pdo->prepare("UPDATE produit SET nom = ?, description = ?, prix = ?, stock = ?, image_url = ? WHERE id = ?");
    return $stmt->execute([
        $this->nom,
        $this->description,
        $this->prix,
        $this->stock,
        $this->image_url,
        $this->id
    ]);
}
```

**Fonctionnalités :**
- Met à jour un produit existant
- Nécessite que l'ID soit défini

##### `delete()` - Supprimer un produit

```php
public function delete()
{
    $pdo = Database::getPDO();
    $stmt = $pdo->prepare("DELETE FROM produit WHERE id = ?");
    return $stmt->execute([$this->id]);
}
```

**Fonctionnalités :**
- Supprime un produit de la base
- Nécessite que l'ID soit défini

---

## 3. Création du contrôleur `ProductController`

### 3.1 Fichier : `app/Controllers/ProductController.php`

Le contrôleur gère les requêtes HTTP et coordonne les interactions entre les modèles et les vues.

#### 3.1.1 Structure de base

```php
<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Product;

final class ProductController extends Controller
{
    // Méthodes d'action
}
```

#### 3.1.2 Méthode `listProducts()` - Afficher la liste

```php
public function listProducts(): void
{
    // Récupère tous les produits
    $products = Product::getAll();
    
    // Affiche la liste des produits
    $this->render('product/list-products', params: [
        'title' => 'Liste des produits',
        'products' => $products
    ]);
}
```

**Fonctionnalités :**
- Récupère tous les produits via le modèle
- Passe les produits à la vue `list-products`
- Définit le titre de la page

#### 3.1.3 Méthode `showCreateProductForm()` - Afficher le formulaire

```php
public function showCreateProductForm(): void
{
    // Affiche le formulaire de création de produit
    $this->render('product/create-product', params: [
        'title' => 'Créer un produit'
    ]);
}
```

**Fonctionnalités :**
- Affiche simplement le formulaire vide
- Utilisé pour la route `GET /products/create`

#### 3.1.4 Méthode `createProduct()` - Traiter la création

Cette méthode est plus complexe car elle gère la validation et la création :

```php
public function createProduct(): void
{
    // 1. Vérification de la méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /products/create');
        return;
    }
    
    // 2. Récupération des données
    $input = $_POST;
    
    // 3. Validation des champs requis
    if (empty($input['nom']) || empty($input['prix']) || empty($input['stock'])) {
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit',
            'message' => 'Les champs "nom", "prix" et "stock" sont requis.',
            'success' => false,
            'old_values' => $input
        ]);
        return;
    }
    
    // 4. Validation du prix
    if (!is_numeric($input['prix']) || floatval($input['prix']) < 0) {
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit',
            'message' => 'Le prix doit être un nombre positif.',
            'success' => false,
            'old_values' => $input
        ]);
        return;
    }
    
    // 5. Validation du stock
    if (!is_numeric($input['stock']) || intval($input['stock']) < 0) {
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit',
            'message' => 'Le stock doit être un entier positif.',
            'success' => false,
            'old_values' => $input
        ]);
        return;
    }
    
    // 6. Validation de l'URL de l'image (si fournie)
    $image_url = $input['image_url'] ?? '';
    if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit',
            'message' => 'L\'URL de l\'image n\'est pas valide.',
            'success' => false,
            'old_values' => $input
        ]);
        return;
    }
    
    // 7. Création de l'instance Product
    $product = new Product();
    $product->setNom($input['nom']);
    $product->setDescription($input['description'] ?? '');
    $product->setPrix(floatval($input['prix']));
    $product->setStock(intval($input['stock']));
    $product->setImageUrl($image_url);
    
    // 8. Sauvegarde et affichage du résultat
    if ($product->save()) {
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit',
            'message' => 'Produit créé avec succès.',
            'success' => true
        ]);
    } else {
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit',
            'message' => 'Erreur lors de la création du produit.',
            'success' => false,
            'old_values' => $input
        ]);
    }
}
```

**Étapes de validation :**
1. Vérifie que la méthode est POST
2. Récupère les données du formulaire
3. Valide les champs obligatoires
4. Valide le format du prix (nombre positif)
5. Valide le format du stock (entier positif)
6. Valide l'URL de l'image si fournie
7. Crée l'instance Product avec les données validées
8. Sauvegarde et affiche le résultat

**Gestion des erreurs :**
- En cas d'erreur, réaffiche le formulaire avec les valeurs saisies (`old_values`)
- Affiche un message d'erreur approprié
- En cas de succès, affiche un message de confirmation

---

## 4. Création des vues

### 4.1 Vue : `app/Views/product/create-product.php`

Cette vue contient le formulaire de création de produit.

#### 4.1.1 Structure générale

```php
<!-- Formulaire pour créer un nouveau produit -->
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>Ajouter un nouveau produit</h2>
    
    <!-- Messages d'erreur/succès -->
    <!-- Formulaire -->
    <!-- Liens de navigation -->
</div>
```

#### 4.1.2 Affichage des messages

```php
<?php if (isset($message)): ?>
    <div style="padding: 10px; margin-bottom: 20px; border-radius: 4px; 
                background-color: <?= isset($success) && $success ? '#d4edda' : '#f8d7da' ?>; 
                color: <?= isset($success) && $success ? '#155724' : '#721c24' ?>;">
        <?= isset($success) && $success ? '✅ ' : '❌ ' ?><?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>
```

**Fonctionnalités :**
- Affiche un message vert en cas de succès
- Affiche un message rouge en cas d'erreur
- Utilise `htmlspecialchars()` pour la sécurité (XSS)

#### 4.1.3 Formulaire HTML

```php
<form method="POST" action="/products" style="display: flex; flex-direction: column; gap: 15px;">
    <!-- Champs du formulaire -->
</form>
```

**Champs du formulaire :**

1. **Nom** (requis)
```php
<input type="text" id="nom" name="nom" required maxlength="150"
       value="<?= isset($old_values['nom']) ? htmlspecialchars($old_values['nom']) : '' ?>"
       placeholder="Entrez le nom du produit">
```

2. **Description** (optionnel)
```php
<textarea id="description" name="description" rows="4"
          placeholder="Entrez la description du produit (optionnel)">
    <?= isset($old_values['description']) ? htmlspecialchars($old_values['description']) : '' ?>
</textarea>
```

3. **Prix** (requis)
```php
<input type="number" id="prix" name="prix" required step="0.01" min="0"
       value="<?= isset($old_values['prix']) ? htmlspecialchars($old_values['prix']) : '' ?>"
       placeholder="0.00">
```

4. **Stock** (requis)
```php
<input type="number" id="stock" name="stock" required min="0"
       value="<?= isset($old_values['stock']) ? htmlspecialchars($old_values['stock']) : '' ?>"
       placeholder="0">
```

5. **URL de l'image** (optionnel)
```php
<input type="url" id="image_url" name="image_url"
       value="<?= isset($old_values['image_url']) ? htmlspecialchars($old_values['image_url']) : '' ?>"
       placeholder="https://exemple.com/image.jpg">
```

#### 4.1.4 Aperçu de l'image

Si une URL d'image valide est fournie, on affiche un aperçu :

```php
<?php if (!empty($old_values['image_url']) && filter_var($old_values['image_url'], FILTER_VALIDATE_URL)): ?>
    <div style="margin-top: 10px;">
        <label>Aperçu de l'image :</label>
        <img src="<?= htmlspecialchars($old_values['image_url']) ?>" 
             alt="Aperçu" 
             style="max-width: 100%; max-height: 300px;">
    </div>
<?php endif; ?>
```

### 4.2 Vue : `app/Views/product/list-products.php`

Cette vue affiche la liste de tous les produits.

#### 4.2.1 Structure générale

```php
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <h2>Liste des produits</h2>
    
    <!-- Bouton d'ajout -->
    <!-- Liste des produits ou message vide -->
</div>
```

#### 4.2.2 Gestion du cas vide

```php
<?php if (empty($products)): ?>
    <div style="text-align: center; padding: 40px;">
        <p>Aucun produit disponible.</p>
        <a href="/products/create">Créer le premier produit</a>
    </div>
<?php else: ?>
    <!-- Affichage de la liste -->
<?php endif; ?>
```

#### 4.2.3 Affichage en grille

```php
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
    <?php foreach ($products as $product): ?>
        <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
            <!-- Contenu de la carte produit -->
        </div>
    <?php endforeach; ?>
</div>
```

**Structure d'une carte produit :**

1. **Image du produit**
```php
<?php if (!empty($product['image_url'])): ?>
    <img src="<?= htmlspecialchars($product['image_url']) ?>" 
         alt="<?= htmlspecialchars($product['nom']) ?>"
         style="max-width: 100%; max-height: 200px;">
<?php else: ?>
    <div>Aucune image</div>
<?php endif; ?>
```

2. **Informations du produit**
```php
<h3><?= htmlspecialchars($product['nom']) ?></h3>
<p><?= htmlspecialchars($product['description']) ?></p>
<div><?= number_format((float)$product['prix'], 2, ',', ' ') ?> €</div>
<div>Stock: <?= htmlspecialchars($product['stock']) ?></div>
```

**Fonctionnalités :**
- Affichage en grille responsive (CSS Grid)
- Chaque produit dans une carte
- Image avec fallback si absente
- Prix formaté en euros
- Protection XSS avec `htmlspecialchars()`

---

## 5. Configuration des routes

### 5.1 Fichier : `public/index.php`

Ajoutez les routes suivantes dans le tableau `$routes` :

```php
$routes = [
    // ... autres routes ...
    
    // Routes pour les produits
    ['GET', '/products', [Mini\Controllers\ProductController::class, 'listProducts']],
    ['GET', '/products/create', [Mini\Controllers\ProductController::class, 'showCreateProductForm']],
    ['POST', '/products', [Mini\Controllers\ProductController::class, 'createProduct']],
];
```

### 5.2 Description des routes

| Méthode | URL | Action | Description |
|---------|-----|--------|-------------|
| GET | `/products` | `listProducts()` | Affiche la liste des produits |
| GET | `/products/create` | `showCreateProductForm()` | Affiche le formulaire de création |
| POST | `/products` | `createProduct()` | Traite la soumission du formulaire |

### 5.3 Ordre des routes

**Important :** Placez la route `POST /products` **après** la route `GET /products/create` pour éviter les conflits.

---

## 6. Résumé et structure finale

### 6.1 Structure des fichiers créés

```
mini_mvc/
├── app/
│   ├── Models/
│   │   └── Product.php              # Modèle Product
│   ├── Controllers/
│   │   └── ProductController.php    # Contrôleur Product
│   └── Views/
│       └── product/
│           ├── create-product.php   # Formulaire de création
│           └── list-products.php   # Liste des produits
├── public/
│   └── index.php                    # Routes (à modifier)
└── docs/
    └── PRODUCT_CRUD.md              # Ce document
```

### 6.2 Flux de fonctionnement

1. **Affichage de la liste** : `GET /products` → `ProductController::listProducts()` → `list-products.php`
2. **Affichage du formulaire** : `GET /products/create` → `ProductController::showCreateProductForm()` → `create-product.php`
3. **Soumission du formulaire** : `POST /products` → `ProductController::createProduct()` → Validation → `Product::save()` → Retour au formulaire avec message

### 6.3 Points importants

#### Sécurité
- ✅ Utilisation de requêtes préparées (protection SQL injection)
- ✅ Validation des données côté serveur
- ✅ Échappement HTML avec `htmlspecialchars()` (protection XSS)
- ✅ Validation des types de données

#### UX (Expérience utilisateur)
- ✅ Messages d'erreur clairs
- ✅ Conservation des valeurs en cas d'erreur
- ✅ Aperçu de l'image
- ✅ Design responsive

#### Architecture MVC
- ✅ Séparation des responsabilités
- ✅ Modèle pour la logique métier
- ✅ Contrôleur pour la logique applicative
- ✅ Vue pour la présentation

### 6.4 Prochaines étapes possibles

- Ajouter la fonctionnalité de modification (UPDATE)
- Ajouter la fonctionnalité de suppression (DELETE)
- Ajouter la pagination pour la liste
- Ajouter un système de recherche/filtrage
- Ajouter la validation côté client (JavaScript)
- Ajouter l'upload d'images au lieu d'URLs

---

## Conclusion

Ce guide vous a montré comment créer un système complet de gestion de produits en suivant l'architecture MVC. Chaque composant a un rôle précis :

- **Modèle** : Gère les interactions avec la base de données
- **Contrôleur** : Gère les requêtes HTTP et la validation
- **Vue** : Affiche les données à l'utilisateur

Cette structure peut être réutilisée pour créer d'autres entités (catégories, commandes, etc.) en suivant le même pattern.

