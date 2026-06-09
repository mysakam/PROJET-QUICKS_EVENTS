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
            'type_evenement' => "ALTER TABLE prestataires ADD COLUMN type_evenement VARCHAR(150) DEFAULT NULL AFTER adresse",
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

    public function findDistinctTypeEvenements(): array
    {
        $this->ensureExtendedColumns();
        $stmt = $this->pdo->query(
            "SELECT DISTINCT type_evenement
             FROM prestataires
             WHERE type_evenement IS NOT NULL
               AND type_evenement <> ''
             ORDER BY type_evenement ASC"
        );

        return array_map(static fn(array $row): string => (string) $row['type_evenement'], $stmt->fetchAll());
    }

    public function findAllWithFilters(array $filters = []): array
    {
        $this->ensureExtendedColumns();

        $sql = "SELECT
                    p.*,
                    GROUP_CONCAT(DISTINCT c.nom ORDER BY c.nom SEPARATOR ', ') AS categories_labels,
                    GROUP_CONCAT(DISTINCT pr.nom ORDER BY pr.nom SEPARATOR ', ') AS prestations_labels
                FROM prestataires p
                LEFT JOIN prestations pr ON pr.id_prestataire = p.id_prestataire
                LEFT JOIN categories c ON c.id_categorie = pr.id_categorie
                WHERE 1=1";

        $params = [];

        if (!empty($filters['q'])) {
            $sql .= " AND (
                p.nom LIKE :q
                OR p.email LIKE :q
                OR p.telephone LIKE :q
                OR p.adresse LIKE :q
                OR p.type_evenement LIKE :q
                OR pr.nom LIKE :q
                OR c.nom LIKE :q
            )";
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['type_evenement'])) {
            $sql .= " AND p.type_evenement = :type_evenement";
            $params['type_evenement'] = $filters['type_evenement'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND c.id_categorie = :category_id";
            $params['category_id'] = (int) $filters['category_id'];
        }

        if (!empty($filters['prestation_id'])) {
            $sql .= " AND pr.id_prestation = :prestation_id";
            $params['prestation_id'] = (int) $filters['prestation_id'];
        }

        $sql .= " GROUP BY p.id_prestataire
                  ORDER BY p.created_at DESC, p.id_prestataire DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

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
                type_evenement,
                description,
                iban,
                bic,
                banque_nom,
                titulaire_compte,
                note_sur_10
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $data['nom'],
            $data['email'] ?: null,
            $data['telephone'] ?: null,
            $data['adresse'] ?: null,
            $data['type_evenement'] ?: null,
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
                 type_evenement = ?,
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
            $data['type_evenement'] ?: null,
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
        $this->ensureFacturesTable();
        $this->pdo->beginTransaction();

        try {
            $affectedDevisStmt = $this->pdo->prepare(
                'SELECT DISTINCT dl.id_devis
                 FROM devis_lignes dl
                 INNER JOIN prestations p ON p.id_prestation = dl.id_prestation
                 WHERE p.id_prestataire = ?'
            );
            $affectedDevisStmt->execute([$idPrestataire]);
            $affectedDevisIds = array_map(
                static fn(array $row): int => (int) $row['id_devis'],
                $affectedDevisStmt->fetchAll()
            );

            $deleteLinesStmt = $this->pdo->prepare(
                'DELETE dl
                 FROM devis_lignes dl
                 INNER JOIN prestations p ON p.id_prestation = dl.id_prestation
                 WHERE p.id_prestataire = ?'
            );
            $deleteLinesStmt->execute([$idPrestataire]);

            if ($affectedDevisIds !== []) {
                $placeholders = implode(',', array_fill(0, count($affectedDevisIds), '?'));

                $updateDevisSql = "UPDATE devis d
                    LEFT JOIN (
                        SELECT id_devis, COALESCE(SUM(montant_ligne), 0) AS total
                        FROM devis_lignes
                        WHERE id_devis IN ($placeholders)
                        GROUP BY id_devis
                    ) lignes ON lignes.id_devis = d.id_devis
                    SET d.montant_total = COALESCE(lignes.total, 0)
                    WHERE d.id_devis IN ($placeholders)";
                $updateDevisStmt = $this->pdo->prepare($updateDevisSql);
                $updateDevisStmt->execute(array_merge($affectedDevisIds, $affectedDevisIds));

                $updateFacturesSql = "UPDATE factures f
                    INNER JOIN devis d ON d.id_devis = f.id_devis
                    SET f.montant_ttc = d.montant_total
                    WHERE f.id_devis IN ($placeholders)";
                $updateFacturesStmt = $this->pdo->prepare($updateFacturesSql);
                $updateFacturesStmt->execute($affectedDevisIds);
            }

            $cleanup = $this->pdo->prepare('DELETE FROM prestations WHERE id_prestataire = ?');
            $cleanup->execute([$idPrestataire]);

            $stmt = $this->pdo->prepare('DELETE FROM prestataires WHERE id_prestataire = ?');
            $stmt->execute([$idPrestataire]);

            $this->pdo->commit();
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
}
