<?php

class EventMediaAdminController extends Controller
{
    private EventMediaModel $eventMediaModel;
    private CategoryModel $categoryModel;
    private PrestationModel $prestationModel;

    public function __construct()
    {
        $this->eventMediaModel = new EventMediaModel();
        $this->categoryModel = new CategoryModel();
        $this->prestationModel = new PrestationModel();
    }

    private function ensureAdmin(): bool
    {
        $client = $_SESSION['client'] ?? null;

        if (!$client || empty($client['is_admin'])) {
            http_response_code(403);
            echo 'Acces reserve a l\'administrateur.';
            return false;
        }

        return true;
    }

    private function themes(): array
    {
        return array_keys($this->themeOptions());
    }

    private function themeOptions(): array
    {
        $options = [
            'mariage' => 'Evenement - Mariage',
            'anniversaire' => 'Evenement - Anniversaire',
            'soiree-theme' => 'Evenement - Soiree a theme',
            'repas-seminaire' => 'Evenement - Repas / Seminaire',
        ];

        try {
            foreach ($this->categoryModel->findAll() as $category) {
                $slug = 'catalogue-category-' . (int) $category['id_categorie'];
                $options[$slug] = 'Catalogue rubrique - ' . $category['nom'];
            }

            foreach ($this->prestationModel->findAllForAdmin() as $prestation) {
                $slug = 'catalogue-prestation-' . (int) $prestation['id_prestation'];
                $options[$slug] = 'Catalogue prestation - ' . $prestation['nom'] . ' (' . $prestation['category_name'] . ')';
            }
        } catch (Throwable $e) {
            // Keep event themes available even if catalogue tables are unavailable.
        }

        return $options;
    }

    private function dbErrorMessage(): string
    {
        return 'La table event_medias est absente. Executez le schema SQL (back/database/schema.sql).';
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $theme = trim($_GET['theme'] ?? '');
        $themeFilter = in_array($theme, $this->themes(), true) ? $theme : null;
        $medias = [];
        try {
            $medias = $this->eventMediaModel->findAll($themeFilter);
        } catch (Throwable $e) {
            $_SESSION['error'] = $this->dbErrorMessage();
        }

        $this->render('event_media/index', [
            'medias' => $medias,
            'themes' => $this->themes(),
            'themeOptions' => $this->themeOptions(),
            'themeFilter' => $themeFilter,
            'pageTitle' => 'Admin medias evenement',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }

    public function create(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('event_media/create', [
            'themes' => $this->themes(),
            'themeOptions' => $this->themeOptions(),
            'pageTitle' => 'Ajouter un media evenement',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }

    public function store(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $data = [
            'theme_slug' => trim($_POST['theme_slug'] ?? ''),
            'media_type' => trim($_POST['media_type'] ?? 'image'),
            'media_url' => trim($_POST['media_url'] ?? ''),
            'title_fr' => trim($_POST['title_fr'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'description_fr' => trim($_POST['description_fr'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'position' => (int)($_POST['position'] ?? 1),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        if (
            !in_array($data['theme_slug'], $this->themes(), true) ||
            !in_array($data['media_type'], ['image', 'video'], true) ||
            $data['media_url'] === '' ||
            $data['title_fr'] === '' ||
            $data['title_en'] === ''
        ) {
            $_SESSION['error'] = 'Champs invalides. Merci de verifier le formulaire.';
            redirect(route('admin_event_medias_create'));
            return;
        }

        try {
            $this->eventMediaModel->create($data);
            $_SESSION['success'] = 'Media ajoute avec succes.';
        } catch (Throwable $e) {
            $_SESSION['error'] = $this->dbErrorMessage();
            redirect(route('admin_event_medias_create'));
            return;
        }
        redirect(route('admin_event_medias'));
    }

    public function edit(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        try {
            $media = $this->eventMediaModel->findById($id);
        } catch (Throwable $e) {
            $_SESSION['error'] = $this->dbErrorMessage();
            redirect(route('admin_event_medias'));
            return;
        }

        if (!$media) {
            http_response_code(404);
            echo 'Media introuvable.';
            return;
        }

        $this->render('event_media/edit', [
            'media' => $media,
            'themes' => $this->themes(),
            'themeOptions' => $this->themeOptions(),
            'pageTitle' => 'Modifier un media evenement',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }

    public function update(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        try {
            $existing = $this->eventMediaModel->findById($id);
        } catch (Throwable $e) {
            $_SESSION['error'] = $this->dbErrorMessage();
            redirect(route('admin_event_medias'));
            return;
        }
        if (!$existing) {
            http_response_code(404);
            echo 'Media introuvable.';
            return;
        }

        $data = [
            'theme_slug' => trim($_POST['theme_slug'] ?? ''),
            'media_type' => trim($_POST['media_type'] ?? 'image'),
            'media_url' => trim($_POST['media_url'] ?? ''),
            'title_fr' => trim($_POST['title_fr'] ?? ''),
            'title_en' => trim($_POST['title_en'] ?? ''),
            'description_fr' => trim($_POST['description_fr'] ?? ''),
            'description_en' => trim($_POST['description_en'] ?? ''),
            'position' => (int)($_POST['position'] ?? 1),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];

        if (
            !in_array($data['theme_slug'], $this->themes(), true) ||
            !in_array($data['media_type'], ['image', 'video'], true) ||
            $data['media_url'] === '' ||
            $data['title_fr'] === '' ||
            $data['title_en'] === ''
        ) {
            $_SESSION['error'] = 'Champs invalides. Merci de verifier le formulaire.';
            redirect(route('admin_event_medias_edit', ['id' => $id]));
            return;
        }

        try {
            $this->eventMediaModel->update($id, $data);
            $_SESSION['success'] = 'Media modifie avec succes.';
        } catch (Throwable $e) {
            $_SESSION['error'] = $this->dbErrorMessage();
            redirect(route('admin_event_medias_edit', ['id' => $id]));
            return;
        }
        redirect(route('admin_event_medias'));
    }

    public function delete(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        try {
            $this->eventMediaModel->delete($id);
            $_SESSION['success'] = 'Media supprime avec succes.';
        } catch (Throwable $e) {
            $_SESSION['error'] = $this->dbErrorMessage();
        }
        redirect(route('admin_event_medias'));
    }
}
