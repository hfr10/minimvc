<!-- Détails d'une commande -->
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <!-- Messages de succès/erreur -->
    <?php if (isset($message) && $message): ?>
        <div style="padding: 15px; margin-bottom: 20px; border-radius: 4px; 
                    background-color: <?= $messageType === 'success' ? '#d4edda' : '#f8d7da' ?>; 
                    color: <?= $messageType === 'success' ? '#155724' : '#721c24' ?>; 
                    border: 1px solid <?= $messageType === 'success' ? '#c3e6cb' : '#f5c6cb' ?>;">
            <?= $messageType === 'success' ? '✅ ' : '❌ ' ?><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!$order): ?>
        <div style="text-align: center; padding: 40px; background-color: #f8d7da; border-radius: 4px; color: #721c24;">
            <h2>Commande introuvable</h2>
            <p>La commande que vous recherchez n'existe pas ou a été supprimée.</p>
            <a href="/orders?user_id=<?= $order['user_id'] ?? 1 ?>" style="color: #007bff; text-decoration: none;">← Retour à mes commandes</a>
        </div>
    <?php else: ?>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2>Détails de la commande #<?= htmlspecialchars($order['id']) ?></h2>
            <a href="/orders?user_id=<?= htmlspecialchars($order['user_id']) ?>" style="padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                ← Retour à mes commandes
            </a>
        </div>
        
        <!-- Informations de la commande -->
        <div style="background-color: white; border: 1px solid #ddd; border-radius: 8px; padding: 25px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Statut</div>
                    <div>
                        <span style="padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: bold;
                            background-color: <?php 
                                if ($order['statut'] === 'validee') {
                                    echo '#d4edda';
                                } elseif ($order['statut'] === 'en_attente') {
                                    echo '#fff3cd';
                                } elseif ($order['statut'] === 'annulee') {
                                    echo '#f8d7da';
                                } else {
                                    echo '#e7f3ff';
                                }
                            ?>;
                            color: <?php 
                                if ($order['statut'] === 'validee') {
                                    echo '#155724';
                                } elseif ($order['statut'] === 'en_attente') {
                                    echo '#856404';
                                } elseif ($order['statut'] === 'annulee') {
                                    echo '#721c24';
                                } else {
                                    echo '#0066cc';
                                }
                            ?>;">
                            <?php 
                                if ($order['statut'] === 'validee') {
                                    echo '✅ Validée';
                                } elseif ($order['statut'] === 'en_attente') {
                                    echo '⏳ En attente';
                                } elseif ($order['statut'] === 'annulee') {
                                    echo '❌ Annulée';
                                } else {
                                    echo htmlspecialchars($order['statut']);
                                }
                            ?>
                        </span>
                    </div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Date de commande</div>
                    <div style="font-weight: bold; color: #333;">
                        <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                    </div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Client</div>
                    <div style="font-weight: bold; color: #333;">
                        <?= htmlspecialchars($order['user_nom'] ?? 'Utilisateur #' . $order['user_id']) ?>
                    </div>
                    <div style="font-size: 12px; color: #666;">
                        <?= htmlspecialchars($order['user_email'] ?? '') ?>
                    </div>
                </div>
                <div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 5px;">Total</div>
                    <div style="font-size: 28px; font-weight: bold; color: #007bff;">
                        <?= number_format((float)$order['total'], 2, ',', ' ') ?> €
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Liste des produits -->
        <div style="background-color: white; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 20px 0; color: #333; font-size: 20px;">Produits commandés</h3>
            
            <?php if (empty($order['products'])): ?>
                <p style="color: #666;">Aucun produit dans cette commande.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($order['products'] as $product): ?>
                        <div style="border: 1px solid #eee; border-radius: 4px; padding: 15px; display: flex; gap: 20px; align-items: center;">
                            <!-- Image du produit -->
                            <div style="width: 80px; height: 80px; flex-shrink: 0;">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($product['image_url']) ?>" 
                                        alt="<?= htmlspecialchars($product['product_nom']) ?>" 
                                        style="width: 100%; height: 100%; object-fit: contain; border-radius: 4px; border: 1px solid #eee;"
                                        onerror="this.style.display='none'"
                                    >
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; background-color: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center; border: 1px solid #eee;">
                                        <span style="color: #999; font-size: 10px;">Pas d'image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Informations du produit -->
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 5px 0; color: #333; font-size: 16px;">
                                    <?= htmlspecialchars($product['product_nom']) ?>
                                </h4>
                                <?php if (!empty($product['categorie_nom'])): ?>
                                    <div style="font-size: 12px; color: #666; margin-bottom: 5px;">
                                        📁 <?= htmlspecialchars($product['categorie_nom']) ?>
                                    </div>
                                <?php endif; ?>
                                <div style="font-size: 14px; color: #666;">
                                    Quantité : <strong><?= htmlspecialchars($product['quantite']) ?></strong>
                                </div>
                            </div>
                            
                            <!-- Prix -->
                            <div style="text-align: right;">
                                <div style="font-size: 18px; font-weight: bold; color: #007bff;">
                                    <?= number_format((float)$product['prix_unitaire'], 2, ',', ' ') ?> €
                                </div>
                                <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                    Sous-total : <?= number_format((float)$product['prix_unitaire'] * (int)$product['quantite'], 2, ',', ' ') ?> €
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

