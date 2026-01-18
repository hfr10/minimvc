## Structure du projet et rôle des fichiers

### Racine
- `composer.json` / `vendor/`: autoloading PSR-4 et dépendances gérées par Composer.
- `README_INSTALL.md`: guide d’installation.
- `README_STRUCTURE.md`: ce document.

### Dossier `public/`
- `public/index.php`: point d’entrée HTTP. Initialise l’autoload, déclare la table des routes et délègue au routeur.

Extrait logique:
```php
require dirname(__DIR__) . '/vendor/autoload.php';

// Déclaration des routes
$routes = [
    ['GET', '/', [Mini\Controllers\HomeController::class, 'index']],
];

// Démarrage du routeur
$router = new Mini\Core\Router($routes);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
```

### Dossier `app/`
Contient le code applicatif (contrôleurs, cœur MVC, vues et configuration locale).

#### `app/Controllers/`
- `HomeController.php`: contrôleur de la page d’accueil. Appelle la méthode `render('home/index', ['title' => 'Mini MVC'])` héritée de `Controller`.

#### `app/Core/`
- `Controller.php`: classe abstraite de base pour les contrôleurs. Fournit `render($view, $params)` qui:
  - extrait les paramètres en variables locales,
  - inclut la vue correspondante et capture son rendu dans `$content` via `ob_start()` / `ob_get_clean()`,
  - inclut `Views/layout.php` qui affiche `$content` et le `<title>`.
- `Router.php`: routeur minimaliste. Reçoit une liste de routes `[method, path, [ControllerClass, action]]` et appelle l’action correspondante.
- `Database.php`: fabrique/retient une instance `PDO` (singleton). Lit les paramètres dans `app/config.ini` et configure `PDO::ATTR_ERRMODE` et `PDO::ATTR_DEFAULT_FETCH_MODE`.

#### `app/Views/`
- `layout.php`: layout principal. Définit la structure HTML, affiche un `<h1>` et injecte le contenu de la vue via `<?= $content ?>`.
- `home/index.php`: vue de la page d’accueil. Affiche un message de bienvenue.

#### `app/config.ini`
Fichier de configuration INI (non versionné en production en général). Contient `DSN`, `USER`, `PASS` pour la base.

### Flux de requête (MVC)
1. Le navigateur appelle `public/index.php`.
2. La table des routes fait correspondre la requête à un contrôleur et une action.
3. Le `Router` instancie le contrôleur ciblé et appelle l’action.
4. Le contrôleur appelle `render(view, params)` qui rend la vue et l’insère dans `layout.php`.
5. La réponse HTML est renvoyée au navigateur.

### Personnalisation rapide
- Ajouter une route: éditez `public/index.php` et ajoutez `['GET', '/chemin', [MonController::class, 'action']]`.
- Créer une vue: placez un fichier `.php` dans `app/Views/...` et appelez `render('dossier/fichier', ['param' => 'valeur'])`.
- Utiliser la base: appelez `Mini\Core\Database::getConnection()` pour obtenir un `PDO`.


