# Active Record
Active Record est un modèle de conception (un pattern) utilisé en développement logiciel (surtout en PHP) pour simplifier la manipulation des données dans une base de données. Il est souvent associé aux frameworks de développement web (Laravel, symfony, etc) et aux systèmes de gestion de bases de données (SGBD) dans le contexte de la programmation orientée objet.
Pour faire simple , Active Record est tout simplement une "technique" de manipulation de données vers une base de données.

Active Record est un design pattern qui consiste à représenter les lignes d'une table d'une base de données sous forme d'objets dans un langage de programmation comme PHP. Chaque objet correspond à une ligne de la table, et chaque attribut de l'objet représente une colonne.

## Avec Active Record :

Les objets encapsulent les données : Chaque instance de classe correspond à une ligne de la base.
Les opérations CRUD (Create, Read, Update, Delete) sont directement réalisées via l'objet. La classe contient généralement les méthodes pour manipuler les données dans la base.
### Exemple en PHP :
```php
class User {
    public $id;
    public $name;
    public $email;

    public function save() {
        // Code pour insérer ou mettre à jour dans la base
    }

    public static function find($id) {
        // Code pour récupérer une ligne dans la base et la transformer en objet
        $query = "SELECT * FROM users WHERE id = :id";
        // Exécution de la requête et hydratation de l'objet
    }

    public function delete() {
        // Code pour supprimer une ligne dans la base
    }
}
```
### Exemple d'utilisation :
```php
// Création d'un nouvel utilisateur
$user = new User();
$user->name = "Jean Dupont";
$user->email = "jean@example.com";
$user->save(); // Insère dans la table `users`

// Lecture d'un utilisateur
$user = User::find(1); // Récupère l'utilisateur avec l'id 1

// Mise à jour
$user->email = "nouvel_email@example.com";
$user->save(); // Met à jour la table

// Suppression
$user->delete(); // Supprime la ligne correspondante
```

## Avantages :
Simplifie la gestion des bases de données.
Permet une correspondance directe entre les objets et les tables.
Rend le code plus lisible et orienté objet.
Inconvénients :
Peut devenir complexe pour des requêtes très spécifiques ou avec des relations complexes.
Moins performant pour des projets nécessitant une optimisation avancée des requêtes SQL.
En résumé, avec Active Record, les objets sont une représentation directe des données de la base, ce qui facilite leur manipulation de manière orientée objet.