# Mini MVC (Starter)

Projet pédagogique minimal: MVC, routes, autoload PSR-4.

## Prérequis
- PHP 8+
- Composer
- Apache (document root sur `public/`)

## Installation
1. `cd mini_mvc`
2. `composer install`

## Structure
- `app/Core`: Router, Controller, Database
- `app/Controllers`: contrôleurs
- `app/Views`: vues + `layout.php`
- `public`: front controller `index.php` + `.htaccess`

## Config
- Éditez `app/config.ini` (DSN/USER/PASS)

## Démarrage
- Servez `public/` et ouvrez `/` dans le navigateur

## Ajouter une route
- Dans `public/index.php`, ajoutez à `$routes` une nouvelle entrée

## Suite (exercice)
- Créer une entité, son contrôleur et vues (CRUD)
