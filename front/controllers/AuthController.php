<?php

class AuthController
{
    public function login()
    {
        // Affiche la vue de connexion
        require_once __DIR__ . '/../helpers/view.php';
        view('auth/login');
    }

    public function register()
    {
        // Affiche la vue d'inscription
        require_once __DIR__ . '/../helpers/view.php';
        view('auth/register');
    }
}
