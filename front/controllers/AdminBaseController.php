<?php

abstract class AdminBaseController extends Controller
{
    protected function ensureAdmin(): bool
    {
        $client = $_SESSION['client'] ?? null;

        if (!$client || empty($client['is_admin'])) {
            http_response_code(403);
            echo 'Acces reserve a l\'administrateur.';
            return false;
        }

        return true;
    }// Méthode pour récupérer la langue de l'utilisateur

    protected function getLang(): string
    {
        return (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr';
    }// Méthode pour récupérer les traductions de l'interface admin
}