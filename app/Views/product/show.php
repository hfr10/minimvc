<!-- Détails du produit -->
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <?php if (!$product): ?>
        <div style="text-align: center; padding: 40px; background-color: #f8d7da; border-radius: 4px; color: #721c24;">
            <h2>Produit introuvable</h2>
            <p>Le produit que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="/products" style="color: #007bff; text-decoration: none;">← Retour à la liste des produits</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- Image du produit -->
            <div>
                <?php if (!empty($product['image_url'])): ?>
                    <img 
                        src="<?= htmlspecialchars($product['image_url']) ?>" 
                        alt="<?= htmlspecialchars($product['nom']) ?>" 
                        style="width: 100%; max-height: 500px; border-radius: 8px; object-fit: contain; border: 1px solid #ddd;"
                        onerror="this.style.display='none'"
                    >
                <?php else: ?>
                    <div style="width: 100%; height: 400px; background-color: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd;">
                        <span style="color: #999; font-size: 18px;">Aucune image disponible</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Informations du produit -->
            <div>
                <h1 style="margin: 0 0 20px 0; color: #333; font-size: 32px;">
                    <?= htmlspecialchars($product['nom']) ?>
                </h1>
                
                <?php if (!empty($product['categorie_nom'])): ?>
                    <div style="margin-bottom: 20px;">
                        <span style="padding: 5px 15px; background-color: #e7f3ff; color: #0066cc; border-radius: 20px; font-size: 14px;">
                            📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                        </span>
                    </div>
                <?php endif; ?>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 36px; font-weight: bold; color: #007bff; margin-bottom: 10px;">
                        <?= number_format((float)$product['prix'], 2, ',', ' ') ?> €
                    </div>
                    <div style="font-size: 16px; color: <?= $product['stock'] > 0 ? '#28a745' : '#dc3545' ?>; font-weight: bold;">
                        <?php if ($product['stock'] > 0): ?>
                            ✅ En stock (<?= htmlspecialchars($product['stock']) ?> disponible<?= $product['stock'] > 1 ? 's' : '' ?>)
                        <?php else: ?>
                            ❌ Stock épuisé
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($product['description'])): ?>
                    <div style="margin-bottom: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
                        <h3 style="margin: 0 0 15px 0; color: #333; font-size: 20px;">Description</h3>
                        <p style="margin: 0; color: #666; line-height: 1.6; white-space: pre-wrap;">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Formulaire d'ajout au panier -->
                <?php if ($product['stock'] > 0): ?>
                    <form method="POST" action="/cart/add-from-form" style="margin-top: 30px;">
                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                        <input type="hidden" name="user_id" value="1">
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <label for="quantite" style="font-weight: bold; color: #333;">Quantité :</label>
                            <input 
                                type="number" 
                                id="quantite" 
                                name="quantite" 
                                value="1" 
                                min="1" 
                                max="<?= htmlspecialchars($product['stock']) ?>"
                                style="width: 80px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                                required
                            >
                            <button 
                                type="submit" 
                                style="flex: 1; padding: 12px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;"
                            >
                                🛒 Ajouter au panier
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div style="padding: 15px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; color: #856404; margin-top: 30px;">
                        ⚠️ Ce produit n'est actuellement pas disponible en stock.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <a href="/products" style="color: #007bff; text-decoration: none;">← Retour à la liste des produits</a>
            <a href="/cart?user_id=1" style="padding: 10px 20px; background-color: #ffc107; color: #000; text-decoration: none; border-radius: 4px; font-weight: bold;">
                🛒 Voir mon panier
            </a>
        </div>
    <?php endif; ?>
</div>

