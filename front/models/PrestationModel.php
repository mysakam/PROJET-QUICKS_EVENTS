<?php

class PrestationModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function findByCategorySlug(string $slug): array
    {
        $sql = "SELECT 
                    p.id_prestation,
                    p.nom,
                    p.description,
                    p.prix_unitaire,
                    c.nom AS category_name,
                    c.slug AS category_slug,
                    pr.nom AS prestataire_name
                FROM prestations p
                INNER JOIN categories c ON p.id_categorie = c.id_categorie
                INNER JOIN prestataires pr ON p.id_prestataire = pr.id_prestataire
                WHERE c.slug = :slug
                  AND p.is_active = 1
                ORDER BY p.nom ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT 
                    p.id_prestation,
                    p.nom,
                    p.description,
                    p.prix_unitaire,
                    c.nom AS category_name,
                    c.slug AS category_slug,
                    pr.nom AS prestataire_name
                FROM prestations p
                INNER JOIN categories c ON p.id_categorie = c.id_categorie
                INNER JOIN prestataires pr ON p.id_prestataire = pr.id_prestataire
                WHERE p.id_prestation = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $prestation = $stmt->fetch();

        return $prestation ?: null;
    }
}