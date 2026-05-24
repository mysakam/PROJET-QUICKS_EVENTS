<?php

class CatalogueController extends Controller
{
    public function index(): void
    {
        $categories = [
            ['name' => 'Mariage', 'slug' => 'mariage'],
            ['name' => 'Anniversaire', 'slug' => 'anniversaire'],
            ['name' => 'Séminaire', 'slug' => 'seminaire'],
        ];

        $this->render('catalogues/index', [
            'categories' => $categories
        ]);
    }

    public function showCategory(string $slug): void
    {
        $prestations = [
            'mariage' => [
                ['id' => 1, 'name' => 'Eden Park', 'category' => 'Mariage', 'price' => 8000],
                ['id' => 2, 'name' => 'Wedding’s Life', 'category' => 'Mariage', 'price' => 6500],
            ],
            'anniversaire' => [
                ['id' => 3, 'name' => 'Happy Time', 'category' => 'Anniversaire', 'price' => 3000],
            ],
            'seminaire' => [
                ['id' => 4, 'name' => 'Your Hero', 'category' => 'Séminaire', 'price' => 9000],
            ],
        ];

        $items = $prestations[$slug] ?? [];

        $this->render('catalogues/category', [
            'slug' => $slug,
            'prestations' => $items
        ]);
    }

    public function showPrestation(int $id): void
    {
        $prestations = [
            1 => ['id' => 1, 'name' => 'Eden Park', 'category' => 'Mariage', 'price' => 8000, 'description' => 'Prestation mariage complète.'],
            2 => ['id' => 2, 'name' => 'Wedding’s Life', 'category' => 'Mariage', 'price' => 6500, 'description' => 'Décoration et mobilier mariage.'],
            3 => ['id' => 3, 'name' => 'Happy Time', 'category' => 'Anniversaire', 'price' => 3000, 'description' => 'Organisation anniversaire.'],
            4 => ['id' => 4, 'name' => 'Your Hero', 'category' => 'Séminaire', 'price' => 9000, 'description' => 'Pack séminaire entreprise.'],
        ];

        $prestation = $prestations[$id] ?? null;

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