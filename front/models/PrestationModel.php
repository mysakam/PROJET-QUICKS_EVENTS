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

    public function findAllForAdmin(): array
    {
        $sql = "SELECT
                    p.id_prestation,
                    p.nom,
                    c.nom AS category_name,
                    c.slug AS category_slug
                FROM prestations p
                INNER JOIN categories c ON p.id_categorie = c.id_categorie
                ORDER BY c.nom ASC, p.nom ASC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function findByPrestataire(int $idPrestataire): array
    {
        $sql = "SELECT
                    p.id_prestation,
                    p.id_categorie,
                    p.nom,
                    p.description,
                    p.prix_unitaire,
                    p.is_active,
                    c.nom AS category_name
                FROM prestations p
                INNER JOIN categories c ON c.id_categorie = p.id_categorie
                WHERE p.id_prestataire = :id_prestataire
                ORDER BY p.created_at DESC, p.id_prestation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_prestataire' => $idPrestataire]);

        return $stmt->fetchAll();
    }

    public function createForPrestataire(array $data): int
    {
        $sql = "INSERT INTO prestations (
                    id_categorie,
                    id_prestataire,
                    nom,
                    description,
                    prix_unitaire,
                    is_active
                ) VALUES (
                    :id_categorie,
                    :id_prestataire,
                    :nom,
                    :description,
                    :prix_unitaire,
                    :is_active
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_categorie' => (int) $data['id_categorie'],
            'id_prestataire' => (int) $data['id_prestataire'],
            'nom' => $data['nom'],
            'description' => $data['description'] !== '' ? $data['description'] : null,
            'prix_unitaire' => (float) $data['prix_unitaire'],
            'is_active' => isset($data['is_active']) ? (int) $data['is_active'] : 1,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function deleteMissingForPrestataire(int $idPrestataire, array $keepIds): void
    {
        $keepIds = array_values(array_filter(array_map('intval', $keepIds), static fn(int $id): bool => $id > 0));

        if ($keepIds === []) {
            $stmt = $this->pdo->prepare('DELETE FROM prestations WHERE id_prestataire = :id_prestataire');
            $stmt->execute(['id_prestataire' => $idPrestataire]);
            return;
        }

        $placeholders = implode(',', array_fill(0, count($keepIds), '?'));
        $sql = "DELETE FROM prestations
                WHERE id_prestataire = ?
                  AND id_prestation NOT IN ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge([$idPrestataire], $keepIds));
    }

    public function syncForPrestataire(int $idPrestataire, array $prestations): void
    {
        $keptIds = [];

        foreach ($prestations as $prestation) {
            if (!empty($prestation['id_prestation'])) {
                $this->updateForPrestataire((int) $prestation['id_prestation'], $idPrestataire, $prestation);
                $keptIds[] = (int) $prestation['id_prestation'];
                continue;
            }

            $prestation['id_prestataire'] = $idPrestataire;
            $keptIds[] = $this->createForPrestataire($prestation);
        }

        $this->deleteMissingForPrestataire($idPrestataire, $keptIds);
    }

    public function updateForPrestataire(int $idPrestation, int $idPrestataire, array $data): void
    {
        $sql = "UPDATE prestations
                SET id_categorie = :id_categorie,
                    nom = :nom,
                    description = :description,
                    prix_unitaire = :prix_unitaire,
                    is_active = :is_active
                WHERE id_prestation = :id_prestation
                  AND id_prestataire = :id_prestataire";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_categorie' => (int) $data['id_categorie'],
            'nom' => $data['nom'],
            'description' => $data['description'] !== '' ? $data['description'] : null,
            'prix_unitaire' => (float) $data['prix_unitaire'],
            'is_active' => isset($data['is_active']) ? (int) $data['is_active'] : 1,
            'id_prestation' => $idPrestation,
            'id_prestataire' => $idPrestataire,
        ]);
    }
}
