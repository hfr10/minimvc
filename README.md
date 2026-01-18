# Mini MVC - Application Web Pédagogique

Un mini framework MVC en PHP créé pour un cours EFREI (B2). C'est une implémentation légère et pédagogique suivant l'architecture Modèle-Vue-Contrôleur.

## 📋 Table des matières

- [Prérequis](#prérequis)
- [Installation](#installation)
  - [1. Cloner/Télécharger le projet](#1-clonerlécharger-le-projet)
  - [2. Installer les dépendances](#2-installer-les-dépendances)
  - [3. Configurer la base de données](#3-configurer-la-base-de-données)
  - [4. Initialiser la base de données](#4-initialiser-la-base-de-données)
- [Lancer le projet](#lancer-le-projet)
- [Identifiants de test](#identifiants-de-test)
- [Structure du projet](#structure-du-projet)
- [Fonctionnalités](#fonctionnalités)

---

## 🔧 Prérequis

- **PHP** 8.0 ou supérieur
- **MySQL** 5.7 ou supérieur (ou MariaDB)
- **Composer** (gestionnaire de dépendances PHP)

### Vérifier les installations

```powershell
# Vérifier PHP
php --version

# Vérifier MySQL
mysql --version

# Vérifier Composer
composer --version
```

---

## 📦 Installation

### 1. Cloner/Télécharger le projet

```powershell
cd C:\Users\YourUsername\Desktop
# Ou clonez le projet s'il est sur Git
```

### 2. Installer les dépendances

```powershell
cd mini_mvc
composer install
```

Cela va créer le dossier `vendor/` et l'autoloader PSR-4.

### 3. Configurer la base de données

Édite le fichier `app/config.ini` :

```ini
DB_NAME = "mini_mvc"
DB_HOST = "127.0.0.1"
DB_USERNAME = "root"
DB_PASSWORD = ""
```

Modifie les valeurs selon ta configuration MySQL :
- `DB_NAME` : nom de la base de données
- `DB_HOST` : serveur MySQL (localhost ou 127.0.0.1)
- `DB_USERNAME` : utilisateur MySQL (généralement "root")
- `DB_PASSWORD` : mot de passe MySQL (vide par défaut en dev local)

### 4. Initialiser la base de données

**Option A : Utiliser le script PHP (recommandé)**

```powershell
php setup-db.php
```

Le script va :
- ✅ Créer la base de données `mini_mvc`
- ✅ Créer toutes les tables
- ✅ Insérer un utilisateur de test

**Option B : Utiliser le script SQL avec MySQL**

```powershell
mysql -u root < database/schema.sql
```

Ou importe le fichier `database/schema.sql` dans phpMyAdmin.

---

## 🚀 Lancer le projet

### Démarrer le serveur de développement

```powershell
cd C:\Users\YourUsername\Desktop\mini_mvc
php -S localhost:2001 -t .\public\
```

Le serveur démarre sur **http://localhost:2001**

### Message d'accueil

```
[Wed Jan  8 10:00:00 2026] PHP 8.4.16 Development Server (http://localhost:2001) started
```

---

## 🔐 Identifiants de test

Une fois la base de données initialisée, tu peux te connecter avec :

| Champ | Valeur |
|-------|--------|
| **Email** | `demo@example.com` |
| **Mot de passe** | `password123` |

### Créer ton propre compte

1. Accède à **http://localhost:2001/register**
2. Remplis le formulaire avec :
   - Nom complet
   - Email
   - Mot de passe (6 caractères minimum)
3. Clique sur "S'inscrire"
4. Tu es automatiquement connecté ! 🎉

---

## 📁 Structure du projet

```
mini_mvc/
├── app/
│   ├── config.ini              # Configuration MySQL
│   ├── Controllers/            # Contrôleurs
│   │   ├── AuthController.php      # Authentification
│   │   ├── HomeController.php
│   │   ├── CartController.php      # Panier
│   │   ├── OrderController.php     # Commandes
│   │   └── ProductController.php   # Produits
│   ├── Core/                   # Framework core
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Model.php
│   │   └── Router.php
│   ├── Models/                 # Modèles de données
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Cart.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   └── ...
│   └── Views/                  # Vues (templates PHP)
│       ├── layout.php          # Template principal
│       ├── auth/               # Pages d'authentification
│       ├── home/
│       ├── product/
│       ├── cart/
│       └── order/
├── database/
│   ├── schema.sql              # Script SQL de création
│   └── migrations.sql
├── public/
│   └── index.php               # Point d'entrée (router)
├── composer.json               # Dépendances
├── setup-db.php                # Script d'initialisation
└── README.md                   # Cette documentation
```

---

## ✨ Fonctionnalités

### 🔑 Authentification
- ✅ Inscription d'utilisateurs
- ✅ Connexion/Déconnexion
- ✅ Sessions PHP
- ✅ Hachage sécurisé des mots de passe (bcrypt)

### 🛍️ Gestion des produits
- ✅ Listing des produits
- ✅ Détails produit
- ✅ Création de produits
- ✅ Gestion des catégories
- ✅ Gestion du stock

### 🛒 Panier d'achat
- ✅ Ajouter au panier
- ✅ Modifier les quantités
- ✅ Supprimer du panier
- ✅ Vider le panier
- ✅ Calcul du total

### 📦 Commandes
- ✅ Créer une commande
- ✅ Consulter ses commandes
- ✅ Voir les détails d'une commande
- ✅ Statuts : en attente, validée, annulée

### 🏗️ Architecture MVC
- ✅ Séparation Modèle/Vue/Contrôleur
- ✅ Router simple et efficace
- ✅ Autoloader PSR-4 (Composer)
- ✅ Classe Database singleton

---

## 🛠️ Routes disponibles

| Méthode | Route | Description |
|---------|-------|-------------|
| **GET** | `/` | Accueil |
| **GET** | `/login` | Formulaire de connexion |
| **POST** | `/login` | Traiter la connexion |
| **GET** | `/register` | Formulaire d'inscription |
| **POST** | `/register` | Traiter l'inscription |
| **GET** | `/logout` | Déconnexion |
| **GET** | `/products` | Liste des produits |
| **GET** | `/products/show?id=1` | Détails d'un produit |
| **GET** | `/products/create` | Formulaire de création |
| **POST** | `/products` | Créer un produit |
| **GET** | `/cart` | Afficher le panier |
| **POST** | `/cart/add-from-form` | Ajouter au panier |
| **POST** | `/cart/remove` | Supprimer du panier |
| **POST** | `/cart/clear` | Vider le panier |
| **GET** | `/orders` | Mes commandes |
| **POST** | `/orders/create` | Créer une commande |

---

## 🐛 Troubleshooting

### Erreur : "Failed opening required 'vendor/autoload.php'"
**Solution** : Lance `composer install`

### Erreur : "Cannot add or update a child row"
**Solution** : La base de données n'est pas initialisée. Lance `php setup-db.php`

### Erreur : "SQLSTATE[42S02]: Base table or view not found"
**Solution** : Les tables ne sont pas créées. Exécute le script SQL.

### Erreur de connexion MySQL
**Solution** : Vérifie les paramètres dans `app/config.ini` :
- Est-ce que MySQL est en cours d'exécution ?
- Les identifiants sont-ils corrects ?
- La base de données existe-t-elle ?

### Le panier ne se remplit pas
**Solution** : Assure-toi d'être connecté (`/login` ou `/register`)

---

## 📝 Notes pour les développeurs

### Architecture
- **Router.php** : Utilise le pattern simple route → contrôleur → action
- **Controller.php** : Classe de base avec méthode `render()` pour les vues
- **Database.php** : Singleton PDO pour les connexions
- **Model.php** : Classe parente factorisant les propriétés communes

### Conventions de nommage
- Classes : `PascalCase` (ex: `ProductController`)
- Méthodes : `camelCase` (ex: `getByUserId()`)
- Variables : `snake_case` (ex: `$product_id`)
- Tables BD : `snake_case` (ex: `categorie`, `produit`)

### Sécurité
- ✅ Hachage bcrypt des mots de passe
- ✅ Requêtes SQL préparées (prévient SQL injection)
- ✅ Validation des emails
- ✅ Sessions PHP pour l'authentification

---

## 📚 Ressources pédagogiques

Voir les fichiers de documentation dans `docs/` :
- `README_START.md` - Guide de démarrage
- `README_STRUCTURE.md` - Structure du projet
- `PRODUCT_CRUD.md` - CRUD produits
- `GUIDE_PANIER.md` - Gestion du panier
- `active-record.md` - Pattern Active Record

---

## 👨‍💻 Auteur

Projet pédagogique EFREI B2 - Mini MVC Framework

---

## 📄 Licence

MIT