## Installation et démarrage (Windows / XAMPP / PHP CLI)

### Prérequis
- PHP 8.x installé et accessible dans le PATH (`php -v`)
- Composer installé (`composer -V`)
- Optionnel: XAMPP si vous préférez Apache/MySQL

### 1) Cloner le projet
```bash
git clone <votre-repo> mini_mvc
cd mini_mvc
```

### 2) Installer les dépendances Composer
```bash
composer install
```
Note: si aucun paquet n’est requis, Composer va simplement générer l’autoload.

### 3) Configurer la base de données (optionnel)
Modifiez `app/config.ini` selon votre environnement:
```
DSN = "mysql:host=127.0.0.1;dbname=mini_mvc;charset=utf8mb4"
USER = "root"
PASS = ""
```
Si vous n’utilisez pas la base pour l’instant, vous pouvez laisser ces valeurs par défaut.

### 4) Lancer le serveur PHP intégré (recommandé en dev)
Positionnez-vous à la racine du projet, puis servez le dossier `public`:
```bash
php -S 127.0.0.1:3004 -t public
```
Ou en utilisant le routeur `public/index.php`:
```bash
php -S 127.0.0.1:3004 public\index.php
```
Ensuite ouvrez `http://127.0.0.1:3004` dans votre navigateur.

### 5) Démarrer via XAMPP (alternative)
1. Lancez XAMPP Control Panel et démarrez "Apache" (et "MySQL" si nécessaire).
2. Placez ce projet sous `C:\xampp\htdocs\mini_mvc`.
3. Ouvrez `http://localhost/mini_mvc/public`.

### 6) Tester l’application
La route `/` doit afficher la page d’accueil avec le titre "Mini MVC".

### Dépannage
- 404 sur `/` avec le serveur intégré: assurez-vous d’avoir bien servi `public` (option `-t public` ou routeur `public/index.php`).
- Problèmes PHP non trouvé: ajoutez le dossier de PHP à la variable d’environnement PATH et redémarrez votre terminal.
- Erreur base de données: vérifiez `app/config.ini` et l’accessibilité du serveur MySQL.


