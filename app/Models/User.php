<?php

// Ici je définit le namespace ou il y aura ma class
namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class User
{
    private $id;
    private $nom;
    private $email;
    private $mot_de_passe;

    // =====================
    // Getters / Setters
    // =====================

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getMotDePasse()
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse($mot_de_passe)
    {
        // Hash le mot de passe
        $this->mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    }

    // =====================
    // Méthodes CRUD
    // =====================

    /**
     * Récupère tous les utilisateurs
     * @return array
     */
    public static function getAll()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM user ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son email
     * @param string $email
     * @return array|null
     */
    public static function findByEmail($email)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouvel utilisateur
     * @return bool
     */
    public function save()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("INSERT INTO user (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        return $stmt->execute([$this->nom, $this->email, $this->mot_de_passe]);
    }

    /**
     * Vérifie le mot de passe d'un utilisateur
     * @param string $mot_de_passe Le mot de passe en clair
     * @return bool
     */
    public function verifyPassword($mot_de_passe)
    {
        return password_verify($mot_de_passe, $this->mot_de_passe);
    }

    /**
     * Authentifie un utilisateur par email et mot de passe
     * @param string $email
     * @param string $mot_de_passe
     * @return array|null L'utilisateur s'il existe et le mot de passe est correct
     */
    public static function authenticate($email, $mot_de_passe)
    {
        $user = self::findByEmail($email);
        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            return $user;
        }
        return null;
    }

    /**
     * Met à jour les informations d’un utilisateur existant
     * @return bool
     */
    public function update()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE user SET nom = ?, email = ? WHERE id = ?");
        return $stmt->execute([$this->nom, $this->email, $this->id]);
    }

    /**
     * Supprime un utilisateur
     * @return bool
     */
    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}
