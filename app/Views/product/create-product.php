<!-- Formulaire pour créer un nouveau produit -->
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2>Ajouter un nouveau produit</h2>
    
    <!-- Message de succès ou d'erreur -->
    <?php if (isset($message)): ?>
        <div style="padding: 10px; margin-bottom: 20px; border-radius: 4px; 
                    background-color: <?= isset($success) && $success ? '#d4edda' : '#f8d7da' ?>; 
                    color: <?= isset($success) && $success ? '#155724' : '#721c24' ?>;">
            <?= isset($success) && $success ? '✅ ' : '❌ ' ?><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/products" style="display: flex; flex-direction: column; gap: 15px;">
        <div>
            <label for="nom" style="display: block; margin-bottom: 5px; font-weight: bold;">Nom du produit :</label>
            <input 
                type="text" 
                id="nom" 
                name="nom" 
                required 
                maxlength="150"
                value="<?= isset($old_values['nom']) ? htmlspecialchars($old_values['nom']) : '' ?>"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                placeholder="Entrez le nom du produit"
            >
        </div>
        
        <div>
            <label for="description" style="display: block; margin-bottom: 5px; font-weight: bold;">Description :</label>
            <textarea 
                id="description" 
                name="description" 
                rows="4"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit;"
                placeholder="Entrez la description du produit (optionnel)"
            ><?= isset($old_values['description']) ? htmlspecialchars($old_values['description']) : '' ?></textarea>
        </div>
        
        <div>
            <label for="prix" style="display: block; margin-bottom: 5px; font-weight: bold;">Prix :</label>
            <input 
                type="number" 
                id="prix" 
                name="prix" 
                required 
                step="0.01"
                min="0"
                value="<?= isset($old_values['prix']) ? htmlspecialchars($old_values['prix']) : '' ?>"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                placeholder="0.00"
            >
        </div>
        
        <div>
            <label for="stock" style="display: block; margin-bottom: 5px; font-weight: bold;">Stock :</label>
            <input 
                type="number" 
                id="stock" 
                name="stock" 
                required 
                min="0"
                value="<?= isset($old_values['stock']) ? htmlspecialchars($old_values['stock']) : '' ?>"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                placeholder="0"
            >
        </div>
        
        <div>
            <label for="categorie_id" style="display: block; margin-bottom: 5px; font-weight: bold;">Catégorie :</label>
            <select 
                id="categorie_id" 
                name="categorie_id"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
            >
                <option value="">-- Sélectionnez une catégorie (optionnel) --</option>
                <?php if (isset($categories)): ?>
                    <?php foreach ($categories as $categorie): ?>
                        <option 
                            value="<?= $categorie['id'] ?>"
                            <?= (isset($old_values['categorie_id']) && $old_values['categorie_id'] == $categorie['id']) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($categorie['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <small style="display: block; margin-top: 5px; color: #666;">Sélectionnez la catégorie du produit (optionnel)</small>
        </div>
        
        <div>
            <label for="image_url" style="display: block; margin-bottom: 5px; font-weight: bold;">URL de l'image :</label>
            <input 
                type="url" 
                id="image_url" 
                name="image_url" 
                value="<?= isset($old_values['image_url']) ? htmlspecialchars($old_values['image_url']) : '' ?>"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                placeholder="https://exemple.com/image.jpg"
            >
            <small style="display: block; margin-top: 5px; color: #666;">Entrez l'URL complète de l'image (optionnel)</small>
        </div>
        
        <!-- Aperçu de l'image si une URL est fournie -->
        <?php if (!empty($old_values['image_url']) && filter_var($old_values['image_url'], FILTER_VALIDATE_URL)): ?>
            <div style="margin-top: 10px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Aperçu de l'image :</label>
                <img 
                    src="<?= htmlspecialchars($old_values['image_url']) ?>" 
                    alt="Aperçu" 
                    style="max-width: 100%; max-height: 300px; border: 1px solid #ccc; border-radius: 4px; object-fit: contain;"
                    onerror="this.style.display='none'"
                >
            </div>
        <?php endif; ?>
        
        <button 
            type="submit" 
            style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;"
        >
            Créer le produit
        </button>
    </form>
    
    <div style="margin-top: 20px; display: flex; gap: 15px;">
        <a href="/products" style="color: #007bff; text-decoration: none;">📋 Voir la liste des produits</a>
        <span style="color: #ccc;">|</span>
        <a href="/" style="color: #007bff; text-decoration: none;">← Retour à l'accueil</a>
    </div>
</div>
