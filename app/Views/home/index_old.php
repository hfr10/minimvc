<!-- Page d'accueil -->
<div style="max-width: 1000px; margin: 0 auto; padding: 40px 20px;">
    
    <?php if ($isConnected): ?>
        <!-- Message de bienvenue personnalisé -->
        <div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; margin-bottom: 40px; text-align: center;">
            <h1 style="margin: 0 0 10px 0; font-size: 36px;">👋 Bonjour, <?= htmlspecialchars($userName) ?> !</h1>
            <p style="margin: 0; font-size: 18px; opacity: 0.95;">Bienvenue sur le Mini MVC Shopping</p>
        </div>
        
        <!-- Cartes d'actions -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <!-- Carte Produits -->
            <div style="padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-size: 40px; margin-bottom: 15px;">🛍️</div>
                <h3 style="color: #333; margin-bottom: 10px;">Parcourir les produits</h3>
                <p style="color: #666; margin-bottom: 15px;">Découvrez notre catalogue complet</p>
                <a href="/products" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">
                    Voir les produits
                </a>
            </div>
            
            <!-- Carte Panier -->
            <div style="padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-size: 40px; margin-bottom: 15px;">🛒</div>
                <h3 style="color: #333; margin-bottom: 10px;">Mon panier</h3>
                <p style="color: #666; margin-bottom: 15px;">Gérez vos articles sélectionnés</p>
                <a href="/cart" style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">
                    Voir mon panier
                </a>
            </div>
            
            <!-- Carte Commandes -->
            <div style="padding: 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="font-size: 40px; margin-bottom: 15px;">📦</div>
                <h3 style="color: #333; margin-bottom: 10px;">Mes commandes</h3>
                <p style="color: #666; margin-bottom: 15px;">Consultez vos commandes passées</p>
                <a href="/orders" style="padding: 10px 20px; background-color: #ffc107; color: #333; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">
                    Voir mes commandes
                </a>
            </div>
        </div>
        
        <!-- Section informations -->
        <div style="padding: 20px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #667eea;">
            <h3 style="color: #333; margin-top: 0;">À propos</h3>
            <p style="color: #666; line-height: 1.6;">
                Bienvenue sur notre plateforme de shopping ! Vous pouvez parcourir nos produits, les ajouter à votre panier 
                et passer vos commandes. Tous les produits sont listés avec leur prix, description et disponibilité en stock.
            </p>
        </div>
        
    <?php else: ?>
        <!-- Message pour utilisateurs non connectés -->
        <div style="padding: 60px 20px; text-align: center; background-color: #f8f9fa; border-radius: 8px;">
            <h1 style="color: #333; font-size: 36px; margin-bottom: 20px;">👋 Bienvenue !</h1>
            <p style="color: #666; font-size: 18px; margin-bottom: 30px;">
                Connectez-vous ou créez un compte pour commencer vos achats
            </p>
            
            <div style="display: flex; gap: 15px; justify-content: center; margin-bottom: 40px;">
                <a href="/login" style="padding: 15px 40px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 16px; display: inline-block;">
                    Se connecter
                </a>
                <a href="/register" style="padding: 15px 40px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 16px; display: inline-block;">
                    S'inscrire
                </a>
            </div>
            
            <div style="max-width: 600px; margin: 0 auto;">
                <h3 style="color: #333; margin-bottom: 20px;">Pourquoi s'inscrire ?</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: left;">
                    <div style="padding: 15px; background-color: white; border-radius: 4px; border-left: 4px solid #007bff;">
                        <strong style="color: #007bff;">🛍️ Parcourir les produits</strong>
                        <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">Découvrez notre sélection de produits</p>
                    </div>
                    <div style="padding: 15px; background-color: white; border-radius: 4px; border-left: 4px solid #28a745;">
                        <strong style="color: #28a745;">🛒 Gérer votre panier</strong>
                        <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">Ajoutez des articles à votre panier</p>
                    </div>
                    <div style="padding: 15px; background-color: white; border-radius: 4px; border-left: 4px solid #ffc107;">
                        <strong style="color: #ffc107;">📦 Passer des commandes</strong>
                        <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">Finalisez vos achats en toute sécurité</p>
                    </div>
                    <div style="padding: 15px; background-color: white; border-radius: 4px; border-left: 4px solid #6c757d;">
                        <strong style="color: #6c757d;">📋 Historique de commandes</strong>
                        <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">Consultez toutes vos commandes</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
</div>


