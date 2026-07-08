<?php

class AdminDevisController extends AdminBaseController
{
    private DevisModel $devisModel;
    private DevisLigneModel $devisLigneModel;
    private FactureModel $factureModel;

    public function __construct()
    {
        $this->devisModel = new DevisModel();
        $this->devisLigneModel = new DevisLigneModel();
        $this->factureModel = new FactureModel();
    }

    public function show(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $devis = $this->devisModel->findById($id);
        if (!$devis) {
            http_response_code(404);
            echo 'Devis introuvable.';
            return;
        }

        $client = null;
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare(
            'SELECT id_client, nom, prenom, email
             FROM clients
             WHERE id_client = :id_client
             LIMIT 1'
        );
        $stmt->execute(['id_client' => (int) $devis['id_client']]);
        $client = $stmt->fetch() ?: null;

        $this->render('admin/devis/show', [
            'devis' => $devis,
            'client' => $client,
            'lignes' => $this->devisLigneModel->findByDevisId($id),
            'facture' => $this->factureModel->findByDevisId($id),
            'pageTitle' => 'Fiche devis',
            'lang' => $this->getLang(),
        ]);
    }
}
