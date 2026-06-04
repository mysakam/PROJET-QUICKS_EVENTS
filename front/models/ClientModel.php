<?php

class ClientModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id_client = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM clients ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function create(string $nom, string $email, string $password): int
    {
        return $this->createWithProfile([
            'nom' => $nom,
            'prenom' => '',
            'email' => $email,
            'password' => $password,
            'telephone' => '',
        ]);
    }

    public function createWithProfile(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO clients (nom, prenom, email, mot_de_passe, telephone)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nom'],
            $data['prenom'] ?? '',
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            ($data['telephone'] ?? '') ?: null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function updateProfile(int $idClient, array $data): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE clients
             SET nom = ?, prenom = ?, email = ?, telephone = ?
             WHERE id_client = ?"
        );

        $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            ($data['telephone'] ?? '') ?: null,
            $idClient,
        ]);
    }

    public function delete(int $idClient): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM clients WHERE id_client = ?");
        $stmt->execute([$idClient]);
    }

    public function updatePassword(int $idClient, string $plainPassword): void
    {
        $stmt = $this->pdo->prepare("UPDATE clients SET mot_de_passe = ? WHERE id_client = ?");
        $stmt->execute([password_hash($plainPassword, PASSWORD_DEFAULT), $idClient]);
    }
}
