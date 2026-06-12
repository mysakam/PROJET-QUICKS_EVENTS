<?php

class CategoriesController extends Controller
{
    public function index(): void
    {
        $categories = [];

        try {
            $stmt = Database::getPdo()->query('SELECT id_categorie, nom, slug FROM categories ORDER BY id_categorie ASC');
            $categories = $stmt->fetchAll();
        } catch (Throwable) {
            $categories = [];
        }

        $this->render('categories/index', [
            'pageTitle' => 'Catégories',
            'categories' => $categories,
        ]);
    }
}
