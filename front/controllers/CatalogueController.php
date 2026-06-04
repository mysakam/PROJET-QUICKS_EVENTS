<?php

class CatalogueController extends Controller
{
    private CategoryModel $categoryModel;
    private PrestationModel $prestationModel;
    private EventMediaModel $eventMediaModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->prestationModel = new PrestationModel();
        $this->eventMediaModel = new EventMediaModel();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->findAll();
        $themeSlugs = array_map(
            static fn(array $c): string => 'catalogue-category-' . (int) $c['id_categorie'],
            $categories
        );
        $categoryMediaMap = $this->eventMediaModel->findFirstByThemes($themeSlugs);

        $this->render('catalogues/index', [
            'categories' => $categories,
            'categoryMediaMap' => $categoryMediaMap,
            'pageTitle' => 'Catalogues',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }

    public function showCategory(string $slug): void
    {
        $category = $this->categoryModel->findBySlug($slug);

        if (!$category) {
            http_response_code(404);
            echo 'Catégorie introuvable';
            return;
        }

        $prestations = $this->prestationModel->findByCategorySlug($slug);
        $categoryThemeSlugs = ['catalogue-category-' . (int) $category['id_categorie']];
        $prestationThemeSlugs = array_map(
            static fn(array $p): string => 'catalogue-prestation-' . (int) $p['id_prestation'],
            $prestations
        );
        $categoryMediaMap = $this->eventMediaModel->findFirstByThemes($categoryThemeSlugs);
        $prestationMediaMap = $this->eventMediaModel->findFirstByThemes($prestationThemeSlugs);

        $this->render('catalogues/category', [
            'category' => $category,
            'slug' => $slug,
            'prestations' => $prestations,
            'categoryMediaMap' => $categoryMediaMap,
            'prestationMediaMap' => $prestationMediaMap,
            'pageTitle' => 'Catalogue ' . $category['nom'],
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }

    public function showPrestation(int $id): void
    {
        $prestation = $this->prestationModel->findById($id);

        if (!$prestation) {
            http_response_code(404);
            echo 'Prestation introuvable';
            return;
        }

        $prestationThemeSlug = 'catalogue-prestation-' . (int) $prestation['id_prestation'];
        $prestationMediaMap = $this->eventMediaModel->findFirstByThemes([$prestationThemeSlug]);

        $this->render('catalogues/show', [
            'prestation' => $prestation,
            'prestationMediaMap' => $prestationMediaMap,
            'pageTitle' => $prestation['nom'],
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }
}
