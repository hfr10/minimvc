<!-- Liste des commandes -->
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Mes commandes</h2>
        <a href="/products" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
            ← Retour aux produits
        </a>
    </div>
    
    <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 60px; background-color: #f8f9fa; border-radius: 8px;">
            <div style="font-size: 64px; margin-bottom: 20px;">📋</div>
            <h3 style="color: #666; margin-bottom: 15px;">Aucune commande</h3>
            <p style="color: #999; margin-bottom: 30px;">Vous n'avez pas encore passé de commande.</p>
            <a href="/products" style="padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">
                Voir les produits
            </a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php foreach ($orders as $order): ?>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
                        <!-- Informations principales -->
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                <h3 style="margin: 0; color: #333; font-size: 20px;">
                                    Commande #<?= htmlspecialchars($order['id']) ?>
                                </h3>
                                <span style="padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold;
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
                            
                            <div style="color: #666; font-size: 14px; margin-bottom: 10px;">
                                <strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
                            </div>
                            
                            <div style="font-size: 24px; font-weight: bold; color: #007bff;">
                                <?= number_format((float)$order['total'], 2, ',', ' ') ?> €
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="/orders/show?id=<?= htmlspecialchars($order['id']) ?>" 
                               style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; text-align: center; font-size: 14px;">
                                👁️ Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="/" style="color: #007bff; text-decoration: none;">← Retour à l'accueil</a>
    </div>
</div>

