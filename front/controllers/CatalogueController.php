<?php

class CatalogueController extends Controller
{
    private CategoryModel $categoryModel;
    private PrestationModel $prestationModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->prestationModel = new PrestationModel();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->findAll();

        $this->render('catalogues/index', [
            'categories' => $categories
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

        $this->render('catalogues/category', [
            'category' => $category,
            'slug' => $slug,
            'prestations' => $prestations
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

        $this->render('catalogues/show', [
            'prestation' => $prestation
        ]);
    }
}