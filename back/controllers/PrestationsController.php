<?php

class PrestationsController extends Controller
{
    public function index(): void
    {
        $prestations = [];

        try {
            $sql = 'SELECT p.id_prestation, p.nom, p.prix_unitaire, c.nom AS categorie FROM prestations p LEFT JOIN categories c ON c.id_categorie = p.id_categorie ORDER BY p.id_prestation DESC';
            $stmt = Database::getPdo()->query($sql);
            $prestations = $stmt->fetchAll();
        } catch (Throwable) {
            $prestations = [];
        }

        $this->render('prestations/index', [
            'pageTitle' => 'Prestations',
            'prestations' => $prestations,
        ]);
    }
}