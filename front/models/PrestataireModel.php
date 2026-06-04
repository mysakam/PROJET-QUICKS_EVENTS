<?php

class PrestataireModel
{
    private PDO $pdo;
    private bool $columnsChecked = false;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    private function ensureExtendedColumns(): void
    {
        if ($this->columnsChecked) {
            return;
        }

        $columns = [
            'iban' => "ALTER TABLE prestataires ADD COLUMN iban VARCHAR(34) DEFAULT NULL AFTER description",
            'bic' => "ALTER TABLE prestataires ADD COLUMN bic VARCHAR(20) DEFAULT NULL AFTER iban",
            'banque_nom' => "ALTER TABLE prestataires ADD COLUMN banque_nom VARCHAR(150) DEFAULT NULL AFTER bic",
            'titulaire_compte' => "ALTER TABLE prestataires ADD COLUMN titulaire_compte VARCHAR(150) DEFAULT NULL AFTER banque_nom",
            'note_sur_10' => "ALTER TABLE prestataires ADD COLUMN note_sur_10 DECIMAL(3,1) DEFAULT NULL AFTER titulaire_compte",
        ];

        foreach ($columns as $column => $sql) {
            $existsStmt = $this->pdo->prepare(
                "SELECT COUNT(*)
                 FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'prestataires'
                   AND COLUMN_NAME = :column"
            );
            $existsStmt->execute(['column' => $column]);

            if ((int) $existsStmt->fetchColumn() === 0) {
                $this->pdo->exec($sql);
            }
        }

        $this->columnsChecked = true;
    }

    private function factureStatuts(): array
    {
        return ['valide', 'facture', 'facture_payee', 'payee'];
    }

    private function ensureFacturesTable(): void
    {
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
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_factures_devis FOREIGN KEY (id_devis) REFERENCES devis (id_devis) ON DELETE CASCADE,
                INDEX idx_factures_statut (statut),
                INDEX idx_factures_created_at (created_at)
            ) ENGINE=InnoDB"
        );
    }

    public function findAll(): array
    {
        $this->ensureExtendedColumns();
        $stmt = $this->pdo->query('SELECT * FROM prestataires ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function findById(int $idPrestataire): array|false
    {
        $this->ensureExtendedColumns();
        $stmt = $this->pdo->prepare('SELECT * FROM prestataires WHERE id_prestataire = ?');
        $stmt->execute([$idPrestataire]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $this->ensureExtendedColumns();
        $stmt = $this->pdo->prepare(
            'INSERT INTO prestataires (
                nom,
                email,
                telephone,
                adresse,
                description,
                iban,
                bic,
                banque_nom,
                titulaire_compte,
                note_sur_10
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $data['nom'],
            $data['email'] ?: null,
            $data['telephone'] ?: null,
            $data['adresse'] ?: null,
            $data['description'] ?: null,
            $data['iban'] ?: null,
            $data['bic'] ?: null,
            $data['banque_nom'] ?: null,
            $data['titulaire_compte'] ?: null,
            ($data['note_sur_10'] !== '' && $data['note_sur_10'] !== null) ? $data['note_sur_10'] : null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $idPrestataire, array $data): void
    {
        $this->ensureExtendedColumns();
        $stmt = $this->pdo->prepare(
            'UPDATE prestataires
             SET nom = ?,
                 email = ?,
                 telephone = ?,
                 adresse = ?,
                 description = ?,
                 iban = ?,
                 bic = ?,
                 banque_nom = ?,
                 titulaire_compte = ?,
                 note_sur_10 = ?
             WHERE id_prestataire = ?'
        );

        $stmt->execute([
            $data['nom'],
            $data['email'] ?: null,
            $data['telephone'] ?: null,
            $data['adresse'] ?: null,
            $data['description'] ?: null,
            $data['iban'] ?: null,
            $data['bic'] ?: null,
            $data['banque_nom'] ?: null,
            $data['titulaire_compte'] ?: null,
            ($data['note_sur_10'] !== '' && $data['note_sur_10'] !== null) ? $data['note_sur_10'] : null,
            $idPrestataire,
        ]);
    }

    public function getActivitySummary(int $idPrestataire): array
    {
        $this->ensureFacturesTable();

        $sql = "SELECT
                    COUNT(DISTINCT d.id_devis) AS total_devis,
                    COUNT(DISTINCT f.id_facture) AS total_factures,
                    COALESCE(SUM(CASE WHEN f.id_facture IS NOT NULL THEN dl.montant_ligne ELSE 0 END), 0) AS montant_factures
                FROM prestations p
                LEFT JOIN devis_lignes dl ON dl.id_prestation = p.id_prestation
                LEFT JOIN devis d ON d.id_devis = dl.id_devis
                LEFT JOIN factures f ON f.id_devis = d.id_devis
                WHERE p.id_prestataire = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idPrestataire]);
        $summary = $stmt->fetch() ?: [];

        return [
            'total_devis' => (int) ($summary['total_devis'] ?? 0),
            'total_factures' => (int) ($summary['total_factures'] ?? 0),
            'montant_factures' => (float) ($summary['montant_factures'] ?? 0),
        ];
    }

    public function getDevisByStatus(int $idPrestataire): array
    {
        $sql = "SELECT d.statut, COUNT(DISTINCT d.id_devis) AS total
                FROM prestations p
                INNER JOIN devis_lignes dl ON dl.id_prestation = p.id_prestation
                INNER JOIN devis d ON d.id_devis = dl.id_devis
                WHERE p.id_prestataire = :id_prestataire
                GROUP BY d.statut
                ORDER BY total DESC, d.statut ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_prestataire' => $idPrestataire]);

        return $stmt->fetchAll();
    }

    public function getFacturesByStatus(int $idPrestataire): array
    {
        $this->ensureFacturesTable();

        $sql = "SELECT f.statut, COUNT(DISTINCT f.id_facture) AS total
                FROM prestations p
                INNER JOIN devis_lignes dl ON dl.id_prestation = p.id_prestation
                INNER JOIN factures f ON f.id_devis = dl.id_devis
                WHERE p.id_prestataire = :id_prestataire
                GROUP BY f.statut
                ORDER BY total DESC, f.statut ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_prestataire' => $idPrestataire]);

        return $stmt->fetchAll();
    }

    public function getRecentDevis(int $idPrestataire, int $limit = 10): array
    {
        $limit = max(1, min(30, $limit));

        $sql = "SELECT
                    d.id_devis,
                    d.reference,
                    d.statut,
                    d.created_at,
                    COALESCE(SUM(dl.montant_ligne), 0) AS montant_prestataire
                FROM prestations p
                INNER JOIN devis_lignes dl ON dl.id_prestation = p.id_prestation
                INNER JOIN devis d ON d.id_devis = dl.id_devis
                WHERE p.id_prestataire = :id_prestataire
                GROUP BY d.id_devis, d.reference, d.statut, d.created_at
                ORDER BY d.created_at DESC, d.id_devis DESC
                LIMIT $limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_prestataire' => $idPrestataire]);

        return $stmt->fetchAll();
    }

    public function getRecentFactures(int $idPrestataire, int $limit = 10): array
    {
        $this->ensureFacturesTable();
        $limit = max(1, min(30, $limit));

        $sql = "SELECT
                    f.id_facture,
                    f.reference,
                    f.statut,
                    f.date_emission,
                    f.date_echeance,
                    f.date_paiement,
                    COALESCE(SUM(dl.montant_ligne), 0) AS montant_prestataire
                FROM prestations p
                INNER JOIN devis_lignes dl ON dl.id_prestation = p.id_prestation
                INNER JOIN factures f ON f.id_devis = dl.id_devis
                WHERE p.id_prestataire = :id_prestataire
                GROUP BY f.id_facture, f.reference, f.statut, f.date_emission, f.date_echeance, f.date_paiement
                ORDER BY f.created_at DESC, f.id_facture DESC
                LIMIT $limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_prestataire' => $idPrestataire]);

        return $stmt->fetchAll();
    }

    public function delete(int $idPrestataire): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM prestataires WHERE id_prestataire = ?');
        $stmt->execute([$idPrestataire]);
    }
}
