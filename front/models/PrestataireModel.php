<?php

class PrestataireModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM prestataires ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function findById(int $idPrestataire): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM prestataires WHERE id_prestataire = ?');
        $stmt->execute([$idPrestataire]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO prestataires (nom, email, telephone, adresse, description) VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $data['nom'],
            $data['email'] ?: null,
            $data['telephone'] ?: null,
            $data['adresse'] ?: null,
            $data['description'] ?: null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $idPrestataire, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE prestataires
             SET nom = ?, email = ?, telephone = ?, adresse = ?, description = ?
             WHERE id_prestataire = ?'
        );

        $stmt->execute([
            $data['nom'],
            $data['email'] ?: null,
            $data['telephone'] ?: null,
            $data['adresse'] ?: null,
            $data['description'] ?: null,
            $idPrestataire,
        ]);
    }

    public function delete(int $idPrestataire): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM prestataires WHERE id_prestataire = ?');
        $stmt->execute([$idPrestataire]);
    }
}
