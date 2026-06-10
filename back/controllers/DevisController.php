<?php

class DevisController extends Controller
{
    public function index(): void
    {
        $devis = [];

        try {
            $sql = 'SELECT d.id_devis, d.reference, d.statut, d.montant_total, d.created_at, c.nom, c.prenom FROM devis d LEFT JOIN clients c ON c.id_client = d.id_client ORDER BY d.id_devis DESC LIMIT 100';
            $stmt = Database::getPdo()->query($sql);
            $devis = $stmt->fetchAll();
        } catch (Throwable) {
            $devis = [];
        }

        $this->render('devis/index', [
            'pageTitle' => 'Devis',
            'devis' => $devis,
        ]);
    }

    public function show(string $id): void
    {
        $devis = null;
        $lignes = [];

        try {
            $stmt = Database::getPdo()->prepare('SELECT d.*, c.nom, c.prenom, c.email FROM devis d LEFT JOIN clients c ON c.id_client = d.id_client WHERE d.id_devis = :id LIMIT 1');
            $stmt->execute(['id' => (int) $id]);
            $devis = $stmt->fetch();

            if ($devis) {
                $sqlLignes = 'SELECT dl.id_ligne, dl.quantite, dl.prix_unitaire, dl.montant_ligne, p.nom AS prestation FROM devis_lignes dl LEFT JOIN prestations p ON p.id_prestation = dl.id_prestation WHERE dl.id_devis = :id ORDER BY dl.id_ligne ASC';
                $stmtLignes = Database::getPdo()->prepare($sqlLignes);
                $stmtLignes->execute(['id' => (int) $id]);
                $lignes = $stmtLignes->fetchAll();
            }
        } catch (Throwable) {
            $devis = null;
            $lignes = [];
        }

        $this->render('devis/show', [
            'pageTitle' => 'Detail devis',
            'devisItem' => $devis,
            'lignes' => $lignes,
        ]);
    }
}
