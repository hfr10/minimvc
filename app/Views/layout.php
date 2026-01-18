<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? htmlspecialchars($title) : 'App' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --text: #374151;
            --shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        header h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        header h1 a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform 0.3s ease;
        }

        header h1 a:hover {
            transform: translateY(-2px);
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }

        nav a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
            position: relative;
        }

        nav a:hover, nav a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
        }

        nav a.active {
            background-color: rgba(255, 255, 255, 0.25);
        }

        main {
            flex: 1;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-warning {
            background-color: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background-color: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(107, 114, 128, 0.3);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        button[type="submit"] {
            font-family: inherit;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-cols-3 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        footer {
            background-color: var(--dark);
            color: white;
            padding: 40px 20px;
            margin-top: auto;
            text-align: center;
            font-size: 14px;
        }

        h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--dark);
        }

        h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background-color: var(--primary);
            color: white;
        }

        .badge-success {
            background-color: var(--success);
        }

        .badge-danger {
            background-color: var(--danger);
        }

        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
                gap: 15px;
            }

            nav ul {
                width: 100%;
                justify-content: center;
            }

            main {
                padding: 20px 10px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$isHome = ($currentPath === '/');
$isProducts = ($currentPath === '/products' || strpos($currentPath, '/products/show') === 0);
$isProductsCreate = ($currentPath === '/products/create');
$isCart = ($currentPath === '/cart');
$isOrders = (strpos($currentPath, '/orders') === 0);
$isConnected = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? null;
?>
<header>
    <div class="container">
        <h1><a href="/">🛍️ <span>EliteShop</span></a></h1>
        <nav>
            <ul>
                <li><a href="/" class="<?= $isHome ? 'active' : '' ?>">🏠 Accueil</a></li>
                <?php if ($isConnected): ?>
                    <li><a href="/products" class="<?= $isProducts ? 'active' : '' ?>">📦 Produits</a></li>
                    <li><a href="/products/create" class="<?= $isProductsCreate ? 'active' : '' ?>">➕ Ajouter</a></li>
                    <li><a href="/cart" class="<?= $isCart ? 'active' : '' ?>">🛒 Panier</a></li>
                    <li><a href="/orders" class="<?= $isOrders ? 'active' : '' ?>">📋 Commandes</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div style="display: flex; gap: 12px; align-items: center;">
            <?php if ($isConnected): ?>
                <span style="color: white; font-weight: 500;">👤 <?= htmlspecialchars($userName) ?></span>
                <a href="/logout" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">Logout</a>
            <?php else: ?>
                <a href="/login" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">Login</a>
                <a href="/register" class="btn btn-success" style="padding: 8px 16px; font-size: 13px;">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container">
    <?= $content ?>
</main>

<footer>
    <p>&copy; 2026 EliteShop. Tous droits réservés. | Design moderne et minimaliste</p>
</footer>
</body>
</html>

