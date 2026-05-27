<?php

class CategoryModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function findAll(): array
    {
        $sql = "SELECT id_categorie, nom, slug
                FROM categories
                ORDER BY nom ASC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT id_categorie, nom, slug
                FROM categories
                WHERE slug = :slug
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);

        $category = $stmt->fetch();

        return $category ?: null;
    }
}