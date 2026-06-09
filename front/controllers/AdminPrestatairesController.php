<?php

class AdminPrestatairesController extends AdminBaseController
{
    private PrestataireModel $prestataireModel;
    private CategoryModel $categoryModel;
    private PrestationModel $prestationModel;

    private function packDescription(array $data): string
    {
        $meta = [];

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

    private function collectPrestationsData(): array
    {
        $ids = $_POST['prestation_id'] ?? [];
        $categoryIds = $_POST['prestation_category_id'] ?? [];
        $names = $_POST['prestation_nom'] ?? [];
        $descriptions = $_POST['prestation_description'] ?? [];
        $prices = $_POST['prestation_prix'] ?? [];

        $rowCount = max(
            is_array($ids) ? count($ids) : 0,
            is_array($categoryIds) ? count($categoryIds) : 0,
            is_array($names) ? count($names) : 0,
            is_array($descriptions) ? count($descriptions) : 0,
            is_array($prices) ? count($prices) : 0,
        );

        $prestations = [];

        for ($index = 0; $index < $rowCount; $index++) {
            $prestations[] = [
                'id_prestation' => (int) (($ids[$index] ?? 0)),
                'id_categorie' => (int) (($categoryIds[$index] ?? 0)),
                'nom' => trim((string) ($names[$index] ?? '')),
                'description' => trim((string) ($descriptions[$index] ?? '')),
                'prix_unitaire' => trim((string) ($prices[$index] ?? '')),
            ];
        }

        return $prestations;
    }

    private function hasPrestationPayload(array $data): bool
    {
        return $data['id_categorie'] > 0 || $data['nom'] !== '' || $data['description'] !== '' || $data['prix_unitaire'] !== '';
    }

    private function filterPrestationsPayload(array $prestations): array
    {
        return array_values(array_filter($prestations, fn(array $prestation): bool => $this->hasPrestationPayload($prestation)));
    }

    private function validatePrestationsPayload(array $prestations): ?string
    {
        foreach ($prestations as $index => $data) {
            $rowLabel = 'Ligne prestation ' . ($index + 1) . ' : ';

            if ($data['id_categorie'] <= 0) {
                return $rowLabel . 'selectionnez une categorie.';
            }

            if ($data['nom'] === '') {
                return $rowLabel . 'le nom de la prestation est obligatoire.';
            }

            if ($data['prix_unitaire'] === '' || !is_numeric($data['prix_unitaire']) || (float) $data['prix_unitaire'] < 0) {
                return $rowLabel . 'le prix unitaire est invalide.';
            }
        }

        return null;
    }

    public function __construct()
    {
        $this->prestataireModel = new PrestataireModel();
        $this->categoryModel = new CategoryModel();
        $this->prestationModel = new PrestationModel();
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $searchQuery = trim($_GET['q'] ?? '');
        $categoryId = (int) ($_GET['category_id'] ?? 0);
        $prestationId = (int) ($_GET['prestation_id'] ?? 0);
        $typeEvenement = trim($_GET['type_evenement'] ?? '');

        $prestataires = $this->prestataireModel->findAllWithFilters([
            'q' => $searchQuery,
            'category_id' => $categoryId > 0 ? $categoryId : null,
            'prestation_id' => $prestationId > 0 ? $prestationId : null,
            'type_evenement' => $typeEvenement !== '' ? $typeEvenement : null,
        ]);

        $this->render('admin/prestataires/index', [
            'prestataires' => $prestataires,
            'searchQuery' => $searchQuery,
            'categories' => $this->categoryModel->findAll(),
            'prestations' => $this->prestationModel->findAllForAdmin(),
            'typeEvenementOptions' => $this->prestataireModel->findDistinctTypeEvenements(),
            'categoryId' => $categoryId,
            'prestationId' => $prestationId,
            'typeEvenement' => $typeEvenement,
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
            'categories' => $this->categoryModel->findAll(),
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
            'prestations' => $this->prestationModel->findByPrestataire($id),
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

        $prestationsData = $this->filterPrestationsPayload($this->collectPrestationsData());
        $prestationError = $this->validatePrestationsPayload($prestationsData);
        if ($prestationError !== null) {
            $_SESSION['error'] = $prestationError;
            redirect(route('admin_prestataires_create'));
            return;
        }

        $data['description'] = $this->packDescription($data);
        $newPrestataireId = $this->prestataireModel->create($data);

        if ($prestationsData !== []) {
            $this->prestationModel->syncForPrestataire($newPrestataireId, $prestationsData);
        }

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
            'categories' => $this->categoryModel->findAll(),
            'prestations' => $this->prestationModel->findByPrestataire($id),
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

        $prestationsData = $this->filterPrestationsPayload($this->collectPrestationsData());
        $prestationError = $this->validatePrestationsPayload($prestationsData);
        if ($prestationError !== null) {
            $_SESSION['error'] = $prestationError;
            redirect(route('admin_prestataires_edit', ['id' => $id]));
            return;
        }

        $data['description'] = $this->packDescription($data);
        $this->prestataireModel->update($id, $data);

        $this->prestationModel->syncForPrestataire($id, $prestationsData);

        $_SESSION['success'] = 'Prestataire modifie avec succes.';
        redirect(route('admin_prestataires_index'));
    }

    public function delete(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        try {
            $this->prestataireModel->delete($id);
            $_SESSION['success'] = 'Prestataire supprime avec succes.';
        } catch (Throwable $exception) {
            $_SESSION['error'] = 'Suppression impossible pour le moment. Les donnees liees a ce prestataire n\'ont pas pu etre nettoyees automatiquement.';
        }

        redirect(route('admin_prestataires_index'));
    }
}
