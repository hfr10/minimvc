<?php

// Active le mode strict pour la vérification des types
declare(strict_types=1);
// Déclare l'espace de noms pour ce contrôleur
namespace Mini\Controllers;
// Importe la classe de base Controller du noyau
use Mini\Core\Controller;
use Mini\Models\Product;
use Mini\Models\Category;

// Déclare la classe finale ProductController qui hérite de Controller
final class ProductController extends Controller
{
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

    /**
     * Affiche les détails d'un produit
     */
    public function show(): void
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Le paramètre id est requis.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $product = Product::findById($id);
        
        $this->render('product/show', params: [
            'title' => $product ? htmlspecialchars($product['nom']) : 'Produit introuvable',
            'product' => $product
        ]);
    }

    public function showCreateProductForm(): void
    {
        // Récupère toutes les catégories
        $categories = Category::getAll();
        
        // Affiche le formulaire de création de produit
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit',
            'categories' => $categories
        ]);
    }

    public function createProduct(): void
    {
        // Vérifie que la méthode HTTP est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products/create');
            return;
        }
        
        // Récupère les données depuis $_POST
        $input = $_POST;
        
        // Récupère toutes les catégories pour la vue
        $categories = Category::getAll();
        
        // Valide les données requises
        if (empty($input['nom']) || empty($input['prix']) || empty($input['stock'])) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Les champs "nom", "prix" et "stock" sont requis.',
                'success' => false,
                'old_values' => $input,
                'categories' => $categories
            ]);
            return;
        }
        
        // Valide le prix (doit être un nombre positif)
        if (!is_numeric($input['prix']) || floatval($input['prix']) < 0) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Le prix doit être un nombre positif.',
                'success' => false,
                'old_values' => $input,
                'categories' => $categories
            ]);
            return;
        }
        
        // Valide le stock (doit être un entier positif)
        if (!is_numeric($input['stock']) || intval($input['stock']) < 0) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Le stock doit être un entier positif.',
                'success' => false,
                'old_values' => $input,
                'categories' => $categories
            ]);
            return;
        }
        
        // Valide l'URL de l'image si fournie
        $image_url = $input['image_url'] ?? '';
        if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'L\'URL de l\'image n\'est pas valide.',
                'success' => false,
                'old_values' => $input,
                'categories' => $categories
            ]);
            return;
        }
        
        // Crée une nouvelle instance Product
        $product = new Product();
        $product->setNom($input['nom']);
        $product->setDescription($input['description'] ?? '');
        $product->setPrix(floatval($input['prix']));
        $product->setStock(intval($input['stock']));
        $product->setImageUrl($image_url);
        $product->setCategorieId(!empty($input['categorie_id']) ? intval($input['categorie_id']) : null);
        
        // Sauvegarde le produit
        if ($product->save()) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Produit créé avec succès.',
                'success' => true,
                'categories' => $categories
            ]);
        } else {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Erreur lors de la création du produit.',
                'success' => false,
                'old_values' => $input,
                'categories' => $categories
            ]);
        }
    }
}

