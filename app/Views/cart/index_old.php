<!-- Vue du panier -->
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Mon panier</h2>
        <a href="/products" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
            ← Continuer les achats
        </a>
    </div>
    
    <!-- Messages de succès/erreur -->
    <?php if (isset($message) && $message): ?>
        <div style="padding: 15px; margin-bottom: 20px; border-radius: 4px; 
                    background-color: <?= $messageType === 'success' ? '#d4edda' : '#f8d7da' ?>; 
                    color: <?= $messageType === 'success' ? '#155724' : '#721c24' ?>; 
                    border: 1px solid <?= $messageType === 'success' ? '#c3e6cb' : '#f5c6cb' ?>;">
            <?= $messageType === 'success' ? '✅ ' : '❌ ' ?><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($cartItems)): ?>
        <div style="text-align: center; padding: 60px; background-color: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 64px; margin-bottom: 20px;">🛒</div>
            <h3 style="color: #666; margin-bottom: 15px;">Votre panier est vide</h3>
            <p style="color: #999; margin-bottom: 30px;">Ajoutez des produits à votre panier pour commencer vos achats.</p>
            <a href="/products" style="padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">
                Voir les produits
            </a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Liste des articles -->
            <div>
                <h3 style="margin-bottom: 20px; color: #333;">Articles dans votre panier</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($cartItems as $item): ?>
                        <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: white; display: flex; gap: 20px;">
                            <!-- Image du produit -->
                            <div style="width: 120px; height: 120px; flex-shrink: 0;">
                                <?php if (!empty($item['image_url'])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($item['image_url']) ?>" 
                                        alt="<?= htmlspecialchars($item['nom']) ?>" 
                                        style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px; border: 1px solid #eee;"
                                        onerror="this.style.display='none'"
                                    >
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; background-color: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; border: 1px solid #eee;">
                                        <span style="color: #999; font-size: 12px;">Pas d'image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Informations du produit -->
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 10px 0; color: #333; font-size: 18px;">
                                    <a href="/products/show?id=<?= htmlspecialchars($item['id']) ?>" style="color: #333; text-decoration: none;">
                                        <?= htmlspecialchars($item['nom']) ?>
                                    </a>
                                </h4>
                                
                                <?php if (!empty($item['categorie_nom'])): ?>
                                    <div style="margin-bottom: 10px;">
                                        <span style="font-size: 12px; color: #666;">📁 <?= htmlspecialchars($item['categorie_nom']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div style="font-size: 20px; font-weight: bold; color: #007bff; margin-bottom: 15px;">
                                    <?= number_format((float)$item['prix'], 2, ',', ' ') ?> €
                                </div>
                                
                                <!-- Gestion de la quantité -->
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <form method="POST" action="/cart/update" style="display: flex; align-items: center; gap: 10px;">
                                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['panier_id']) ?>">
                                        <label for="quantite_<?= htmlspecialchars($item['panier_id']) ?>" style="font-size: 14px; color: #666;">Quantité :</label>
                                        <input 
                                            type="number" 
                                            id="quantite_<?= htmlspecialchars($item['panier_id']) ?>" 
                                            name="quantite" 
                                            value="<?= htmlspecialchars($item['quantite']) ?>" 
                                            min="1" 
                                            max="<?= htmlspecialchars($item['stock']) ?>"
                                            style="width: 60px; padding: 5px; border: 1px solid #ccc; border-radius: 4px;"
                                            required
                                        >
                                        <button 
                                            type="submit" 
                                            style="padding: 5px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;"
                                        >
                                            Mettre à jour
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="/cart/remove" style="margin: 0;">
                                        <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['panier_id']) ?>">
                                        <button 
                                            type="submit" 
                                            style="padding: 5px 15px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"
                                        >
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                </div>
                                
                                <div style="margin-top: 10px; font-size: 14px; color: #666;">
                                    Sous-total : <strong><?= number_format((float)$item['prix'] * (int)$item['quantite'], 2, ',', ' ') ?> €</strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Bouton vider le panier -->
                <div style="margin-top: 20px;">
                    <form method="POST" action="/cart/clear">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                        <button 
                            type="submit" 
                            style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;"
                            onclick="return confirm('Êtes-vous sûr de vouloir vider tout votre panier ?')"
                        >
                            🗑️ Vider le panier
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Résumé de la commande -->
            <div>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: white; position: sticky; top: 20px;">
                    <h3 style="margin: 0 0 20px 0; color: #333; font-size: 20px;">Résumé de la commande</h3>
                    
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #666;">Sous-total :</span>
                            <span style="font-weight: bold;"><?= number_format((float)$total, 2, ',', ' ') ?> €</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #666;">Frais de livraison :</span>
                            <span style="font-weight: bold;">Gratuit</span>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 30px; padding-top: 20px; border-top: 2px solid #007bff;">
                        <span style="font-size: 20px; font-weight: bold; color: #333;">Total :</span>
                        <span style="font-size: 24px; font-weight: bold; color: #007bff;"><?= number_format((float)$total, 2, ',', ' ') ?> €</span>
                    </div>
                    
                    <form method="POST" action="/orders/create">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                        <button 
                            type="submit" 
                            style="width: 100%; padding: 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 18px; font-weight: bold;"
                        >
                            ✅ Valider la commande
                        </button>
                    </form>
                    
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="/products" style="color: #007bff; text-decoration: none; font-size: 14px;">
                            ← Continuer les achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

