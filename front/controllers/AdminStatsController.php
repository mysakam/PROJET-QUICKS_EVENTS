<?php

class AdminStatsController extends AdminBaseController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    private function ensureFacturesTable(): void
    {
        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS factures (
                id_facture INT AUTO_INCREMENT PRIMARY KEY,
                id_devis INT NOT NULL,
                reference VARCHAR(50) NOT NULL UNIQUE,
                statut VARCHAR(50) NOT NULL DEFAULT 'emise',
                montant_ttc DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
                date_emission DATE DEFAULT NULL,
                date_echeance DATE DEFAULT NULL,
                date_paiement DATE DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_factures_devis FOREIGN KEY (id_devis) REFERENCES devis (id_devis) ON DELETE CASCADE,
                INDEX idx_factures_statut (statut),
                INDEX idx_factures_created_at (created_at)
            ) ENGINE=InnoDB"
        );
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->ensureFacturesTable();

        $kpis = [
            'clients' => (int) $this->pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
            'prestataires' => (int) $this->pdo->query('SELECT COUNT(*) FROM prestataires')->fetchColumn(),
            'devis' => (int) $this->pdo->query('SELECT COUNT(*) FROM devis')->fetchColumn(),
            'factures' => (int) $this->pdo->query('SELECT COUNT(*) FROM factures')->fetchColumn(),
            'prestations' => (int) $this->pdo->query('SELECT COUNT(*) FROM prestations')->fetchColumn(),
            'ca_total' => (float) $this->pdo->query('SELECT COALESCE(SUM(montant_total), 0) FROM devis')->fetchColumn(),
            'ca_factures' => (float) $this->pdo->query('SELECT COALESCE(SUM(montant_ttc), 0) FROM factures')->fetchColumn(),
        ];

        $devisByStatut = $this->pdo->query(
            'SELECT statut, COUNT(*) AS total FROM devis GROUP BY statut ORDER BY total DESC'
        )->fetchAll();

        $facturesByStatut = $this->pdo->query(
            'SELECT statut, COUNT(*) AS total FROM factures GROUP BY statut ORDER BY total DESC'
        )->fetchAll();

        $topClients = $this->pdo->query(
            'SELECT c.nom, c.prenom, c.email, COALESCE(SUM(d.montant_total), 0) AS ca_client
             FROM clients c
             LEFT JOIN devis d ON d.id_client = c.id_client
             GROUP BY c.id_client
             ORDER BY ca_client DESC
             LIMIT 10'
        )->fetchAll();

        $topPrestataires = $this->pdo->query(
            'SELECT p.nom, COALESCE(SUM(dl.montant_ligne), 0) AS ca_prestataire
             FROM prestataires p
             LEFT JOIN prestations pr ON pr.id_prestataire = p.id_prestataire
             LEFT JOIN devis_lignes dl ON dl.id_prestation = pr.id_prestation
             GROUP BY p.id_prestataire
             ORDER BY ca_prestataire DESC
             LIMIT 10'
        )->fetchAll();

        $topPrestations = $this->pdo->query(
            'SELECT pr.nom, COALESCE(SUM(dl.montant_ligne), 0) AS ca_prestation
             FROM prestations pr
             LEFT JOIN devis_lignes dl ON dl.id_prestation = pr.id_prestation
             GROUP BY pr.id_prestation
             ORDER BY ca_prestation DESC
             LIMIT 10'
        )->fetchAll();

        $topCategories = $this->pdo->query(
            'SELECT c.nom, COALESCE(SUM(dl.montant_ligne), 0) AS ca_categorie
             FROM categories c
             LEFT JOIN prestations pr ON pr.id_categorie = c.id_categorie
             LEFT JOIN devis_lignes dl ON dl.id_prestation = pr.id_prestation
             GROUP BY c.id_categorie
             ORDER BY ca_categorie DESC
             LIMIT 10'
        )->fetchAll();

        $this->render('admin/stats/index', [
            'kpis' => $kpis,
            'devisByStatut' => $devisByStatut,
            'facturesByStatut' => $facturesByStatut,
            'topClients' => $topClients,
            'topPrestataires' => $topPrestataires,
            'topPrestations' => $topPrestations,
            'topCategories' => $topCategories,
            'pageTitle' => 'Statistiques Admin',
            'lang' => $this->getLang(),
        ]);
    }
}
