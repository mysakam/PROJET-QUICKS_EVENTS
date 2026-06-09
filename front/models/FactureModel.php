<?php

class FactureModel
{
    private PDO $pdo;
    private bool $tableChecked = false;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    private function ensureTable(): void
    {
        if ($this->tableChecked) {
            return;
        }

        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS factures (
                id_facture INT AUTO_INCREMENT PRIMARY KEY,
                id_devis INT NOT NULL,
                reference VARCHAR(50) NOT NULL UNIQUE,
                statut VARCHAR(50) NOT NULL DEFAULT 'emise',
                montant_ttc DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
                date_emission DATE DEFAULT NULL,
                date_echeance DATE DEFAULT NULL,
                date_paiement DATE DEFAULT NULL,
                date_envoi_mail DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_factures_devis FOREIGN KEY (id_devis) REFERENCES devis (id_devis) ON DELETE CASCADE,
                INDEX idx_factures_statut (statut),
                INDEX idx_factures_created_at (created_at)
            ) ENGINE=InnoDB"
        );

        $columnCheck = $this->pdo->prepare(
            "SELECT COUNT(*)
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'factures'
               AND COLUMN_NAME = 'date_envoi_mail'"
        );
        $columnCheck->execute();

        if ((int) $columnCheck->fetchColumn() === 0) {
            $this->pdo->exec('ALTER TABLE factures ADD COLUMN date_envoi_mail DATETIME DEFAULT NULL AFTER date_paiement');
        }

        $indexCheck = $this->pdo->prepare(
            "SELECT COUNT(*)
             FROM INFORMATION_SCHEMA.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'factures'
               AND INDEX_NAME = 'uniq_factures_devis'"
        );
        $indexCheck->execute();

        if ((int) $indexCheck->fetchColumn() === 0) {
            $this->pdo->exec('ALTER TABLE factures ADD UNIQUE KEY uniq_factures_devis (id_devis)');
        }

        $this->tableChecked = true;
    }

    public function statuses(): array
    {
        return ['en_attente_validation', 'emise', 'envoyee', 'payee', 'annulee', 'en_retard'];
    }

    public function findAll(): array
    {
        $this->ensureTable();

        $sql = "SELECT
                    f.id_facture,
                    f.reference,
                    f.statut,
                    f.montant_ttc,
                    f.created_at AS facture_created_at,
                    f.date_emission,
                    f.date_echeance,
                    f.date_paiement,
                    f.date_envoi_mail,
                    d.reference AS devis_reference,
                    d.created_at AS devis_created_at,
                    d.date_evenement AS date_reservation,
                    c.nom AS client_nom,
                    c.prenom AS client_prenom
                FROM factures f
                INNER JOIN devis d ON d.id_devis = f.id_devis
                INNER JOIN clients c ON c.id_client = d.id_client
                ORDER BY f.created_at DESC, f.id_facture DESC";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function findById(int $idFacture): ?array
    {
        $this->ensureTable();

        $sql = "SELECT
                    f.id_facture,
                    f.id_devis,
                    f.reference,
                    f.statut,
                    f.montant_ttc,
                    f.created_at AS facture_created_at,
                    f.date_emission,
                    f.date_echeance,
                    f.date_paiement,
                    f.date_envoi_mail,
                    d.reference AS devis_reference,
                    d.created_at AS devis_created_at,
                    d.date_evenement AS date_reservation,
                    d.statut AS devis_statut,
                    d.montant_total AS devis_montant_total,
                    d.message_client,
                    d.date_evenement,
                    c.id_client,
                    c.nom AS client_nom,
                    c.prenom AS client_prenom,
                    c.email AS client_email
                FROM factures f
                INNER JOIN devis d ON d.id_devis = f.id_devis
                INNER JOIN clients c ON c.id_client = d.id_client
                WHERE id_facture = :id_facture
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_facture' => $idFacture]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findDevisWithoutFacture(): array
    {
        $this->ensureTable();

        $sql = "SELECT
                    d.id_devis,
                    d.reference,
                    d.montant_total,
                    c.nom,
                    c.prenom
                FROM devis d
                INNER JOIN clients c ON c.id_client = d.id_client
                LEFT JOIN factures f ON f.id_devis = d.id_devis
                WHERE f.id_facture IS NULL
                ORDER BY d.created_at DESC, d.id_devis DESC";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(array $data): int
    {
        $this->ensureTable();

        $sql = "INSERT INTO factures (
                    id_devis,
                    reference,
                    statut,
                    montant_ttc,
                    date_emission,
                    date_echeance,
                    date_paiement,
                    date_envoi_mail
                ) VALUES (
                    :id_devis,
                    :reference,
                    :statut,
                    :montant_ttc,
                    :date_emission,
                    :date_echeance,
                    :date_paiement,
                    :date_envoi_mail
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_devis' => $data['id_devis'],
            'reference' => $data['reference'],
            'statut' => $data['statut'],
            'montant_ttc' => $data['montant_ttc'],
            'date_emission' => $data['date_emission'] ?: null,
            'date_echeance' => $data['date_echeance'] ?: null,
            'date_paiement' => $data['date_paiement'] ?: null,
            'date_envoi_mail' => $data['date_envoi_mail'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $idFacture, array $data): bool
    {
        $this->ensureTable();

        $sql = "UPDATE factures
                SET statut = :statut,
                    montant_ttc = :montant_ttc,
                    date_emission = :date_emission,
                    date_echeance = :date_echeance,
                    date_paiement = :date_paiement,
                    date_envoi_mail = :date_envoi_mail
                WHERE id_facture = :id_facture";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_facture' => $idFacture,
            'statut' => $data['statut'],
            'montant_ttc' => $data['montant_ttc'],
            'date_emission' => $data['date_emission'] ?: null,
            'date_echeance' => $data['date_echeance'] ?: null,
            'date_paiement' => $data['date_paiement'] ?: null,
            'date_envoi_mail' => $data['date_envoi_mail'] ?? null,
        ]);
    }

    public function findByDevisId(int $idDevis): ?array
    {
        $this->ensureTable();

        $stmt = $this->pdo->prepare('SELECT * FROM factures WHERE id_devis = ? LIMIT 1');
        $stmt->execute([$idDevis]);
        $facture = $stmt->fetch();

        return $facture ?: null;
    }

    public function markAsSent(int $idFacture): void
    {
        $this->ensureTable();

        $stmt = $this->pdo->prepare(
            "UPDATE factures
             SET statut = 'envoyee',
                 date_envoi_mail = NOW()
             WHERE id_facture = ?"
        );
        $stmt->execute([$idFacture]);
    }

    public function delete(int $idFacture): bool
    {
        $this->ensureTable();
        $stmt = $this->pdo->prepare('DELETE FROM factures WHERE id_facture = :id_facture');
        return $stmt->execute(['id_facture' => $idFacture]);
    }
}
