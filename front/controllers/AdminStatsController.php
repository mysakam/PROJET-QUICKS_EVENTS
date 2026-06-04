<?php

class AdminStatsController extends AdminBaseController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $kpis = [
            'clients' => (int) $this->pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
            'prestataires' => (int) $this->pdo->query('SELECT COUNT(*) FROM prestataires')->fetchColumn(),
            'devis' => (int) $this->pdo->query('SELECT COUNT(*) FROM devis')->fetchColumn(),
            'prestations' => (int) $this->pdo->query('SELECT COUNT(*) FROM prestations')->fetchColumn(),
            'ca_total' => (float) $this->pdo->query('SELECT COALESCE(SUM(montant_total), 0) FROM devis')->fetchColumn(),
        ];

        $devisByStatut = $this->pdo->query(
            'SELECT statut, COUNT(*) AS total FROM devis GROUP BY statut ORDER BY total DESC'
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
            'topClients' => $topClients,
            'topPrestataires' => $topPrestataires,
            'topPrestations' => $topPrestations,
            'topCategories' => $topCategories,
            'pageTitle' => 'Statistiques Admin',
            'lang' => $this->getLang(),
        ]);
    }
}
