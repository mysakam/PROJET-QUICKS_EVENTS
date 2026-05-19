<?php
class DashboardController extends Controller
{
    public function index()
    {
        // Vérifie si l'utilisateur est authentifié
        $authMiddleware = new AuthMiddleware();
        if (!$authMiddleware->handle()) {
            return;
        }

        // Affiche la vue du tableau de bord
        $this->render('dashboard/index');
    }
}