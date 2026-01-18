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
        // Utilise l'utilisateur connecté, sinon redirige vers login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        $user_id = $_SESSION['user_id'];
        
        // Ici je récupère les produits du panier de l'user authentifié
        $cartItems = Cart::getByUserId($user_id);
        // Ici on récupère le prix total du panier
        $total = Cart::getTotalByUserId($user_id);
        
        $message = null;
        $messageType = null;
        
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'added') {
                $message = 'Produit ajouté au panier avec succès !';
                $messageType = 'success';
            } elseif ($_GET['success'] === 'updated') {
                $message = 'Quantité mise à jour avec succès !';
                $messageType = 'success';
            } elseif ($_GET['success'] === 'removed') {
                $message = 'Article supprimé du panier avec succès !';
                $messageType = 'success';
            } elseif ($_GET['success'] === 'cleared') {
                $message = 'Panier vidé avec succès !';
                $messageType = 'success';
            }
        }
        
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'stock_insuffisant') {
                $message = 'Stock insuffisant pour cette quantité.';
                $messageType = 'error';
            } elseif ($_GET['error'] === 'update_failed') {
                $message = 'Erreur lors de la mise à jour.';
                $messageType = 'error';
            } elseif ($_GET['error'] === 'delete_failed') {
                $message = 'Erreur lors de la suppression.';
                $messageType = 'error';
            } elseif ($_GET['error'] === 'clear_failed') {
                $message = 'Erreur lors du vidage du panier.';
                $messageType = 'error';
            }
        }
        
        $this->render('cart/index', params: [
            'title' => 'Mon panier',
            'cartItems' => $cartItems,
            'total' => $total,
            'user_id' => $user_id,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    /**
     * Ajoute un produit au panier (API JSON)
     */
    public function add(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        if (empty($input['user_id']) || empty($input['product_id']) || empty($input['quantite'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "user_id", "product_id" et "quantite" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Vérifie que le produit existe
        $product = Product::findById($input['product_id']);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Produit introuvable.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Vérifie le stock disponible
        if ($product['stock'] < $input['quantite']) {
            http_response_code(400);
            echo json_encode(['error' => 'Stock insuffisant.'], JSON_PRETTY_PRINT);
            return;
        }
        
        $cart = new Cart();
        $cart->setUserId($input['user_id']);
        $cart->setProductId($input['product_id']);
        $cart->setQuantite($input['quantite']);
        
        if ($cart->save()) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Produit ajouté au panier avec succès.'
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout au panier.'], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Ajoute un produit au panier depuis un formulaire HTML
     */
    public function addFromForm(): void
    {
        // Vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products');
            return;
        }
        
        $product_id = $_POST['product_id'] ?? null;
        $quantite = intval($_POST['quantite'] ?? 1);
        $user_id = $_SESSION['user_id'];
        
        if (!$product_id) {
            header('Location: /products');
            return;
        }
        
        // Vérifie que le produit existe
        $product = Product::findById($product_id);
        if (!$product) {
            header('Location: /products');
            return;
        }
        
        // Vérifie le stock disponible
        if ($product['stock'] < $quantite) {
            header('Location: /products/show?id=' . $product_id . '&error=stock_insuffisant');
            return;
        }
        
        $cart = new Cart();
        $cart->setUserId($user_id);
        $cart->setProductId($product_id);
        $cart->setQuantite($quantite);
        
        // DEBUG
        error_log("CartController::addFromForm - user_id=$user_id, product_id=$product_id, quantite=$quantite");
        
        if ($cart->save()) {
            error_log("CartController::addFromForm - SUCCESS");
            header('Location: /cart?user_id=' . $user_id . '&success=added');
        } else {
            error_log("CartController::addFromForm - FAILED");
            header('Location: /products/show?id=' . $product_id . '&error=add_failed');
        }
    }

    /**
     * Met à jour la quantité d'un produit dans le panier
     */
    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
            header('Location: /cart');
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        if (empty($input['cart_id']) || empty($input['quantite'])) {
            header('Location: /cart?user_id=' . ($_GET['user_id'] ?? 1) . '&error=missing_fields');
            return;
        }
        
        // Récupère l'article du panier
        $pdo = \Mini\Core\Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM panier WHERE id = ?");
        $stmt->execute([$input['cart_id']]);
        $cartItem = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$cartItem) {
            header('Location: /cart?user_id=' . ($_GET['user_id'] ?? 1) . '&error=item_not_found');
            return;
        }
        
        // Vérifie le stock
        $product = Product::findById($cartItem['product_id']);
        if ($product['stock'] < $input['quantite']) {
            header('Location: /cart?user_id=' . $cartItem['user_id'] . '&error=stock_insuffisant');
            return;
        }
        
        $cart = new Cart();
        $cart->setId($input['cart_id']);
        $cart->setUserId($cartItem['user_id']);
        $cart->setProductId($cartItem['product_id']);
        $cart->setQuantite($input['quantite']);
        
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
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        $cart_id = $input['cart_id'] ?? $_GET['cart_id'] ?? null;
        
        if (!$cart_id) {
            header('Location: /cart?user_id=' . ($_GET['user_id'] ?? 1) . '&error=missing_cart_id');
            return;
        }
        
        // Récupère l'article pour obtenir le user_id
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
     * Vide le panier d'un utilisateur
     */
    public function clear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input === null) {
            $input = $_POST;
        }
        
        $user_id = $input['user_id'] ?? $_GET['user_id'] ?? 1;
        
        if (Cart::clearByUserId($user_id)) {
            header('Location: /cart?user_id=' . $user_id . '&success=cleared');
        } else {
            header('Location: /cart?user_id=' . $user_id . '&error=clear_failed');
        }
    }
}

