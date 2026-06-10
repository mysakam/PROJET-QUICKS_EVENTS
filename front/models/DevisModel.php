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

    public function findJourneyByClientId(int $clientId, array $filters = []): array
    {
        $sql = "SELECT
                    d.id_devis,
                    d.reference AS devis_reference,
                    d.statut AS devis_statut,
                    d.created_at AS devis_created_at,
                    d.montant_total AS devis_montant_total,
                    f.id_facture,
                    f.reference AS facture_reference,
                    f.statut AS facture_statut,
                    f.created_at AS facture_created_at,
                    f.montant_ttc AS facture_montant_ttc
                FROM devis d
                LEFT JOIN factures f ON f.id_devis = d.id_devis
                WHERE d.id_client = :id_client";

        $params = ['id_client' => $clientId];

        if (!empty($filters['devis_statut'])) {
            $sql .= " AND d.statut = :devis_statut";
            $params['devis_statut'] = $filters['devis_statut'];
        }

        if (!empty($filters['facture_statut'])) {
            if ($filters['facture_statut'] === '__none__') {
                $sql .= " AND f.id_facture IS NULL";
            } else {
                $sql .= " AND f.statut = :facture_statut";
                $params['facture_statut'] = $filters['facture_statut'];
            }
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(d.created_at) >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(d.created_at) <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }

        $sql .= " ORDER BY d.created_at DESC, d.id_devis DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function updateStatus(int $idDevis, string $statut): void
    {
        $stmt = $this->pdo->prepare('UPDATE devis SET statut = ? WHERE id_devis = ?');
        $stmt->execute([$statut, $idDevis]);
    }
}
