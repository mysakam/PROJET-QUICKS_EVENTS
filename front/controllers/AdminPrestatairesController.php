<?php

class AdminPrestatairesController extends AdminBaseController
{
    private PrestataireModel $prestataireModel;

    private function packDescription(array $data): string
    {
        $meta = [];

        if (!empty($data['type_evenement'])) {
            $meta[] = 'Type evenement: ' . $data['type_evenement'];
        }

        if (!empty($data['capacite_max'])) {
            $meta[] = 'Capacite max: ' . $data['capacite_max'];
        }

        if (!empty($data['prix_offre'])) {
            $meta[] = 'Prix offre: ' . $data['prix_offre'];
        }

        $base = trim($data['description'] ?? '');
        $metaBlock = implode(' | ', $meta);

        if ($base !== '' && $metaBlock !== '') {
            return $base . PHP_EOL . '[' . $metaBlock . ']';
        }

        if ($base !== '') {
            return $base;
        }

        return $metaBlock;
    }

    public function __construct()
    {
        $this->prestataireModel = new PrestataireModel();
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('admin/prestataires/index', [
            'prestataires' => $this->prestataireModel->findAll(),
            'pageTitle' => 'Admin prestataires',
            'lang' => $this->getLang(),
        ]);
    }

    public function create(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('admin/prestataires/create', [
            'pageTitle' => 'Ajouter un prestataire',
            'lang' => $this->getLang(),
        ]);
    }

    public function show(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $prestataire = $this->prestataireModel->findById($id);

        if (!$prestataire) {
            http_response_code(404);
            echo 'Prestataire introuvable.';
            return;
        }

        $this->render('admin/prestataires/show', [
            'prestataire' => $prestataire,
            'activitySummary' => $this->prestataireModel->getActivitySummary($id),
            'devisByStatus' => $this->prestataireModel->getDevisByStatus($id),
            'facturesByStatus' => $this->prestataireModel->getFacturesByStatus($id),
            'recentDevis' => $this->prestataireModel->getRecentDevis($id, 12),
            'recentFactures' => $this->prestataireModel->getRecentFactures($id, 12),
            'pageTitle' => 'Fiche prestataire',
            'lang' => $this->getLang(),
        ]);
    }

    public function store(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
            'adresse' => trim($_POST['adresse'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'type_evenement' => trim($_POST['type_evenement'] ?? ''),
            'capacite_max' => trim($_POST['capacite_max'] ?? ''),
            'prix_offre' => trim($_POST['prix_offre'] ?? ''),
            'iban' => strtoupper(trim($_POST['iban'] ?? '')),
            'bic' => strtoupper(trim($_POST['bic'] ?? '')),
            'banque_nom' => trim($_POST['banque_nom'] ?? ''),
            'titulaire_compte' => trim($_POST['titulaire_compte'] ?? ''),
            'note_sur_10' => trim($_POST['note_sur_10'] ?? ''),
        ];

        if ($data['nom'] === '') {
            $_SESSION['error'] = 'Le nom du prestataire est obligatoire.';
            redirect(route('admin_prestataires_create'));
            return;
        }

        $data['description'] = $this->packDescription($data);
        $this->prestataireModel->create($data);
        $_SESSION['success'] = 'Prestataire ajoute avec succes.';
        redirect(route('admin_prestataires_index'));
    }

    public function edit(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $prestataire = $this->prestataireModel->findById($id);

        if (!$prestataire) {
            http_response_code(404);
            echo 'Prestataire introuvable.';
            return;
        }

        $this->render('admin/prestataires/edit', [
            'prestataire' => $prestataire,
            'pageTitle' => 'Modifier un prestataire',
            'lang' => $this->getLang(),
        ]);
    }

    public function update(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $existing = $this->prestataireModel->findById($id);
        if (!$existing) {
            http_response_code(404);
            echo 'Prestataire introuvable.';
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
            'adresse' => trim($_POST['adresse'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'type_evenement' => trim($_POST['type_evenement'] ?? ''),
            'capacite_max' => trim($_POST['capacite_max'] ?? ''),
            'prix_offre' => trim($_POST['prix_offre'] ?? ''),
            'iban' => strtoupper(trim($_POST['iban'] ?? '')),
            'bic' => strtoupper(trim($_POST['bic'] ?? '')),
            'banque_nom' => trim($_POST['banque_nom'] ?? ''),
            'titulaire_compte' => trim($_POST['titulaire_compte'] ?? ''),
            'note_sur_10' => trim($_POST['note_sur_10'] ?? ''),
        ];

        if ($data['nom'] === '') {
            $_SESSION['error'] = 'Le nom du prestataire est obligatoire.';
            redirect(route('admin_prestataires_edit', ['id' => $id]));
            return;
        }

        $data['description'] = $this->packDescription($data);
        $this->prestataireModel->update($id, $data);
        $_SESSION['success'] = 'Prestataire modifie avec succes.';
        redirect(route('admin_prestataires_index'));
    }

    public function delete(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->prestataireModel->delete($id);
        $_SESSION['success'] = 'Prestataire supprime avec succes.';
        redirect(route('admin_prestataires_index'));
    }
}
