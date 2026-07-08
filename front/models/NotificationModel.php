<?php

class NotificationModel
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
            "CREATE TABLE IF NOT EXISTS notifications (
                id_notification INT AUTO_INCREMENT PRIMARY KEY,
                recipient_role VARCHAR(50) NOT NULL DEFAULT 'admin',
                type VARCHAR(60) NOT NULL,
                title VARCHAR(180) NOT NULL,
                message TEXT NOT NULL,
                payload_json TEXT DEFAULT NULL,
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                read_at DATETIME DEFAULT NULL,
                INDEX idx_notifications_recipient_read_created (recipient_role, is_read, created_at),
                INDEX idx_notifications_created_at (created_at)
            ) ENGINE=InnoDB"
        );

        $this->tableChecked = true;
    }

    public function createForAdmin(string $type, string $title, string $message, array $payload = []): int
    {
        $this->ensureTable();

        $sql = "INSERT INTO notifications (
                    recipient_role,
                    type,
                    title,
                    message,
                    payload_json,
                    is_read,
                    created_at
                ) VALUES (
                    :recipient_role,
                    :type,
                    :title,
                    :message,
                    :payload_json,
                    0,
                    NOW()
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'recipient_role' => 'admin',
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'payload_json' => $payload !== [] ? json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findRecentForAdmin(int $limit = 5): array
    {
        $this->ensureTable();
        $limit = max(1, min(20, $limit));

        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM notifications
             WHERE recipient_role = 'admin'
             ORDER BY is_read ASC, created_at DESC, id_notification DESC
             LIMIT $limit"
        );
        $stmt->execute();

        $rows = $stmt->fetchAll();

        return array_map([$this, 'normalizeRow'], $rows);
    }

    public function countUnreadForAdmin(): int
    {
        $this->ensureTable();

        $stmt = $this->pdo->query(
            "SELECT COUNT(*)
             FROM notifications
             WHERE recipient_role = 'admin'
               AND is_read = 0"
        );

        return (int) $stmt->fetchColumn();
    }

    public function markAsRead(int $idNotification): bool
    {
        $this->ensureTable();

        $stmt = $this->pdo->prepare(
            "UPDATE notifications
             SET is_read = 1,
                 read_at = COALESCE(read_at, NOW())
             WHERE id_notification = :id_notification
               AND recipient_role = 'admin'"
        );

        return $stmt->execute(['id_notification' => $idNotification]);
    }

    public function findByIdForAdmin(int $idNotification): ?array
    {
        $this->ensureTable();

        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM notifications
             WHERE id_notification = :id_notification
               AND recipient_role = 'admin'
             LIMIT 1"
        );
        $stmt->execute(['id_notification' => $idNotification]);

        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        return $this->normalizeRow($row);
    }

    public function deleteForAdmin(int $idNotification): bool
    {
        $this->ensureTable();

        $stmt = $this->pdo->prepare(
            "DELETE FROM notifications
             WHERE id_notification = :id_notification
               AND recipient_role = 'admin'"
        );

        return $stmt->execute(['id_notification' => $idNotification]);
    }

    private function normalizeRow(array $row): array
    {
        $row['payload'] = [];

        if (!empty($row['payload_json'])) {
            $decoded = json_decode((string) $row['payload_json'], true);
            if (is_array($decoded)) {
                $row['payload'] = $decoded;
            }
        }

        return $row;
    }
}
