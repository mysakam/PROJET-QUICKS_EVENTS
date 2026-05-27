<?php

class DevisModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO devis (
                    id_client,
                    reference,
                    statut,
                    date_evenement,
                    message_client,
                    montant_total
                ) VALUES (
                    :id_client,
                    :reference,
                    :statut,
                    :date_evenement,
                    :message_client,
                    :montant_total
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_client' => $data['id_client'],
            'reference' => $data['reference'],
            'statut' => $data['statut'],
            'date_evenement' => $data['date_evenement'],
            'message_client' => $data['message_client'],
            'montant_total' => $data['montant_total'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT *
                FROM devis
                WHERE id_devis = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $devis = $stmt->fetch();

        return $devis ?: null;
    }

    public function findByClientId(int $clientId): array
    {
        $sql = "SELECT *
                FROM devis
                WHERE id_client = :id_client
                ORDER BY created_at DESC, id_devis DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_client' => $clientId]);

        return $stmt->fetchAll();
    }
}