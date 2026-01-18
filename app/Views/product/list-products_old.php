<!-- Liste des produits -->
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Liste des produits</h2>
        <a href="/products/create" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
            ➕ Ajouter un produit
        </a>
    </div>
    
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 40px; background-color: #f8f9fa; border-radius: 4px;">
            <p style="color: #666; font-size: 18px;">Aucun produit disponible.</p>
            <a href="/products/create" style="color: #007bff; text-decoration: none;">Créer le premier produit</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php foreach ($products as $product): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Image du produit -->
                    <?php if (!empty($product['image_url'])): ?>
                        <div style="margin-bottom: 15px; text-align: center;">
                            <img 
                                src="<?= htmlspecialchars($product['image_url']) ?>" 
                                alt="<?= htmlspecialchars($product['nom']) ?>" 
                                style="max-width: 100%; max-height: 200px; border-radius: 4px; object-fit: contain;"
                                onerror="this.style.display='none'"
                            >
                        </div>
                    <?php else: ?>
                        <div style="margin-bottom: 15px; text-align: center; padding: 40px; background-color: #f8f9fa; border-radius: 4px;">
                            <span style="color: #999; font-size: 14px;">Aucune image</span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Informations du produit -->
                    <h3 style="margin: 0 0 10px 0; color: #333; font-size: 20px;">
                        <?= htmlspecialchars($product['nom']) ?>
                    </h3>
                    
                    <?php if (!empty($product['description'])): ?>
                        <p style="margin: 0 0 15px 0; color: #666; font-size: 14px; line-height: 1.5;">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                        <div>
                            <div style="font-size: 24px; font-weight: bold; color: #007bff;">
                                <?= number_format((float)$product['prix'], 2, ',', ' ') ?> €
                            </div>
                            <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                Stock: <?= htmlspecialchars($product['stock']) ?>
                            </div>
                            <?php if (!empty($product['categorie_nom'])): ?>
                                <div style="font-size: 12px; color: #28a745; margin-top: 5px;">
                                    📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px; display: flex; gap: 10px;">
                        <a href="/products/show?id=<?= htmlspecialchars($product['id']) ?>" 
                           style="flex: 1; padding: 8px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; text-align: center; font-size: 14px;">
                            👁️ Voir détails
                        </a>
                        <form method="POST" action="/cart/add-from-form" style="flex: 1; margin: 0;">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="quantite" value="1">
                            <input type="hidden" name="user_id" value="1">
                            <button type="submit" 
                                    style="width: 100%; padding: 8px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;"
                                    <?= $product['stock'] <= 0 ? 'disabled title="Stock épuisé"' : '' ?>>
                                🛒 Ajouter au panier
                            </button>
                        </form>
                    </div>
                    
                    <div style="margin-top: 10px; font-size: 12px; color: #999;">
                        ID: <?= htmlspecialchars($product['id']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
        <a href="/" style="color: #007bff; text-decoration: none;">← Retour à l'accueil</a>
        <a href="/cart?user_id=1" style="padding: 10px 20px; background-color: #ffc107; color: #000; text-decoration: none; border-radius: 4px; font-weight: bold;">
            🛒 Voir mon panier
        </a>
    </div>
</div>

