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

    public function findAllWithJourneySummary(): array
    {
        $sql = "SELECT
                    c.*,
                    COALESCE(ds.devis_count, 0) AS devis_count,
                    ld.reference AS last_devis_reference,
                    ld.statut AS last_devis_statut,
                    ld.created_at AS last_devis_created_at,
                    COALESCE(fs.factures_count, 0) AS factures_count,
                    lf.reference AS last_facture_reference,
                    lf.statut AS last_facture_statut,
                    lf.created_at AS last_facture_created_at
                FROM clients c
                LEFT JOIN (
                    SELECT
                        d.id_client,
                        COUNT(*) AS devis_count,
                        MAX(d.id_devis) AS last_devis_id
                    FROM devis d
                    GROUP BY d.id_client
                ) ds ON ds.id_client = c.id_client
                LEFT JOIN devis ld ON ld.id_devis = ds.last_devis_id
                LEFT JOIN (
                    SELECT
                        d.id_client,
                        COUNT(f.id_facture) AS factures_count,
                        MAX(f.id_facture) AS last_facture_id
                    FROM devis d
                    LEFT JOIN factures f ON f.id_devis = d.id_devis
                    GROUP BY d.id_client
                ) fs ON fs.id_client = c.id_client
                LEFT JOIN factures lf ON lf.id_facture = fs.last_facture_id
                ORDER BY c.created_at DESC";

        return $this->pdo->query($sql)->fetchAll();
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
