<?php

class PrestataireDisponibiliteModel
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
            "CREATE TABLE IF NOT EXISTS prestataire_disponibilites (
                id_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
                id_prestataire INT NOT NULL,
                date_evenement DATE NOT NULL,
                statut VARCHAR(20) NOT NULL DEFAULT 'disponible',
                commentaire VARCHAR(255) DEFAULT NULL,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_disponibilites_prestataire
                    FOREIGN KEY (id_prestataire) REFERENCES prestataires (id_prestataire) ON DELETE CASCADE,
                CONSTRAINT chk_disponibilites_statut
                    CHECK (statut IN ('disponible', 'indisponible')),
                UNIQUE KEY uniq_prestataire_date (id_prestataire, date_evenement),
                INDEX idx_disponibilites_date (date_evenement)
            ) ENGINE=InnoDB"
        );

        $this->tableChecked = true;
    }

    public function upsert(int $idPrestataire, string $dateEvenement, string $statut, string $commentaire = ''): void
    {
        $this->ensureTable();

        $statut = $statut === 'indisponible' ? 'indisponible' : 'disponible';
        $commentaire = trim($commentaire);

        $sql = "INSERT INTO prestataire_disponibilites (id_prestataire, date_evenement, statut, commentaire)
                VALUES (:id_prestataire, :date_evenement, :statut, :commentaire)
                ON DUPLICATE KEY UPDATE
                    statut = VALUES(statut),
                    commentaire = VALUES(commentaire),
                    updated_at = NOW()";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_prestataire' => $idPrestataire,
            'date_evenement' => $dateEvenement,
            'statut' => $statut,
            'commentaire' => $commentaire !== '' ? $commentaire : null,
        ]);
    }

    public function deleteForDate(int $idPrestataire, string $dateEvenement): void
    {
        $this->ensureTable();

        $stmt = $this->pdo->prepare(
            'DELETE FROM prestataire_disponibilites WHERE id_prestataire = :id_prestataire AND date_evenement = :date_evenement'
        );

        $stmt->execute([
            'id_prestataire' => $idPrestataire,
            'date_evenement' => $dateEvenement,
        ]);
    }

    public function findForPrestataireMonth(int $idPrestataire, int $year, int $month): array
    {
        $this->ensureTable();

        $month = max(1, min(12, $month));
        $year = max(2000, min(2100, $year));

        $monthStart = sprintf('%04d-%02d-01', $year, $month);
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $stmt = $this->pdo->prepare(
            "SELECT id_disponibilite, id_prestataire, date_evenement, statut, commentaire, updated_at
             FROM prestataire_disponibilites
             WHERE id_prestataire = :id_prestataire
               AND date_evenement BETWEEN :month_start AND :month_end
             ORDER BY date_evenement ASC"
        );

        $stmt->execute([
            'id_prestataire' => $idPrestataire,
            'month_start' => $monthStart,
            'month_end' => $monthEnd,
        ]);

        return $stmt->fetchAll();
    }

    public function findStatusesForPrestatairesByDate(array $prestataireIds, string $dateEvenement): array
    {
        $this->ensureTable();

        $prestataireIds = array_values(array_unique(array_filter(array_map('intval', $prestataireIds), static fn(int $id): bool => $id > 0)));
        if ($prestataireIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($prestataireIds), '?'));

        $sql = "SELECT id_prestataire, date_evenement, statut, commentaire
                FROM prestataire_disponibilites
                WHERE date_evenement = ?
                  AND id_prestataire IN ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge([$dateEvenement], $prestataireIds));

        $rows = $stmt->fetchAll();
        $indexed = [];

        foreach ($rows as $row) {
            $indexed[(int) $row['id_prestataire']] = $row;
        }

        return $indexed;
    }
}
