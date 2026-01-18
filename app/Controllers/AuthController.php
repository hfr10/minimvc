<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;

final class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function loginForm(): void
    {
        // Évite de montrer le formulaire si l'utilisateur est déjà connecté
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            return;
        }

        $this->render('auth/login', params: [
            'title' => 'Connexion'
        ]);
    }

    /**
     * Traite la connexion utilisateur
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            return;
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            header('Location: /login?error=missing_fields');
            return;
        }

        // Authentifie l'utilisateur
        $user = User::authenticate($email, $password);

        if ($user) {
            // Démarre la session et stocke l'ID utilisateur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nom'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: /');
        } else {
            header('Location: /login?error=invalid_credentials');
        }
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function registerForm(): void
    {
        // Évite de montrer le formulaire si l'utilisateur est déjà connecté
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            return;
        }

        $this->render('auth/register', params: [
            'title' => 'Inscription'
        ]);
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            return;
        }

        $nom = $_POST['nom'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $passwordConfirm = $_POST['password_confirm'] ?? null;

        // Validations
        if (!$nom || !$email || !$password || !$passwordConfirm) {
            header('Location: /register?error=missing_fields');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: /register?error=invalid_email');
            return;
        }

        if ($password !== $passwordConfirm) {
            header('Location: /register?error=passwords_dont_match');
            return;
        }

        if (strlen($password) < 6) {
            header('Location: /register?error=password_too_short');
            return;
        }

        // Vérifie si l'email existe déjà
        if (User::findByEmail($email)) {
            header('Location: /register?error=email_exists');
            return;
        }

        // Crée le nouvel utilisateur
        $user = new User();
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setMotDePasse($password);

        if ($user->save()) {
            // Récupère l'utilisateur créé et le connecte
            $userCreated = User::findByEmail($email);
            $_SESSION['user_id'] = $userCreated['id'];
            $_SESSION['user_name'] = $userCreated['nom'];
            $_SESSION['user_email'] = $userCreated['email'];

            header('Location: /');
        } else {
            header('Location: /register?error=registration_failed');
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        // Détruit la session
        session_unset();
        session_destroy();

        header('Location: /');
    }
}
