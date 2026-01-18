<!-- Liste des produits - Design moderne -->
<div style="margin-bottom: 50px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="margin: 0; font-size: 36px;">🛍️ Our Catalog</h2>
            <p style="color: #6b7280; margin: 10px 0 0 0; font-size: 16px;">Discover premium products from top sellers</p>
        </div>
        <a href="/products/create" class="btn btn-primary" style="padding: 14px 28px; font-size: 14px;">➕ Add Product</a>
    </div>
    
    <?php if (empty($products)): ?>
        <!-- Empty State -->
        <div class="card" style="text-align: center; padding: 60px 30px;">
            <div style="font-size: 64px; margin-bottom: 20px;">📦</div>
            <h3 style="margin: 0 0 12px 0; font-size: 24px;">No Products Yet</h3>
            <p style="color: #6b7280; margin: 0 0 30px 0; font-size: 16px;">Start by adding your first product to the catalog</p>
            <a href="/products/create" class="btn btn-primary" style="display: inline-block;">Add First Product</a>
        </div>
    <?php else: ?>
        <!-- Products Grid -->
        <div class="grid grid-cols-3">
            <?php foreach ($products as $product): ?>
                <div class="card" style="display: flex; flex-direction: column; overflow: hidden;">
                    <!-- Product Image Container -->
                    <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); display: flex; align-items: center; justify-content: center; overflow: hidden; margin: -24px -24px 20px -24px; padding: 0;">
                        <?php if (!empty($product['image_url'])): ?>
                            <img 
                                src="<?= htmlspecialchars($product['image_url']) ?>" 
                                alt="<?= htmlspecialchars($product['nom']) ?>" 
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'"
                                onerror="this.parentElement.innerHTML='<div style=\"font-size: 48px; color: #d1d5db;\">🖼️</div>'"
                            >
                        <?php else: ?>
                            <div style="font-size: 48px; color: #d1d5db;">🖼️</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Product Info -->
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <!-- Title -->
                        <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 600; color: #1f2937; line-height: 1.4;">
                            <?= htmlspecialchars($product['nom']) ?>
                        </h3>
                        
                        <!-- Category Badge -->
                        <?php if (!empty($product['categorie_nom'])): ?>
                            <span class="badge" style="width: fit-content; margin-bottom: 12px; background-color: #6366f1; font-size: 12px;">
                                📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                            </span>
                        <?php endif; ?>
                        
                        <!-- Description -->
                        <?php if (!empty($product['description'])): ?>
                            <p style="color: #6b7280; font-size: 13px; margin: 0 0 12px 0; line-height: 1.5; flex: 1;">
                                <?= htmlspecialchars(substr($product['description'], 0, 80)) . (strlen($product['description']) > 80 ? '...' : '') ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Price and Stock -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; margin: 12px 0;">
                            <div style="font-size: 24px; font-weight: 700; color: #6366f1;">
                                <?= number_format((float)$product['prix'], 2, '.', ' ') ?> €
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Stock</div>
                                <div style="font-size: 18px; font-weight: 600; color: <?= $product['stock'] > 0 ? '#10b981' : '#ef4444' ?>;">
                                    <?= $product['stock'] ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div style="display: flex; gap: 10px; margin-top: auto;">
                            <a href="/products/show?id=<?= htmlspecialchars($product['id']) ?>" 
                               class="btn btn-secondary" 
                               style="flex: 1; padding: 10px 12px; font-size: 13px; text-align: center;">
                                👁️ View
                            </a>
                            <form method="POST" action="/cart/add-from-form" style="flex: 1; margin: 0;">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                <input type="hidden" name="quantite" value="1">
                                <input type="hidden" name="user_id" value="1">
                                <button type="submit" 
                                        class="btn btn-success" 
                                        style="width: 100%; padding: 10px 12px; font-size: 13px; border: none;"
                                        <?= $product['stock'] <= 0 ? 'disabled title="Out of stock"' : '' ?>>
                                    🛒 Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Footer Navigation -->
<div style="display: flex; justify-content: space-between; align-items: center; padding-top: 40px; border-top: 1px solid #e5e7eb; margin-top: 40px;">
    <a href="/" style="color: #6366f1; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 8px;">
        ← Back to Home
    </a>
    <a href="/cart?user_id=1" class="btn btn-warning" style="padding: 12px 24px;">
        🛒 View My Cart
    </a>
</div>
