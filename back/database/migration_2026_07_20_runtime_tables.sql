USE quickevents;

-- Migration incrementale pour bases existantes.
-- Objectif:
-- 1. creer notifications si la table est absente
-- 2. creer prestataire_disponibilites si la table est absente
-- 3. aligner factures avec date_envoi_mail et l'unicite sur id_devis

DROP PROCEDURE IF EXISTS migrate_quickevents_runtime_tables;

DELIMITER $$

CREATE PROCEDURE migrate_quickevents_runtime_tables()
BEGIN
    DECLARE notifications_exists INT DEFAULT 0;
    DECLARE disponibilites_exists INT DEFAULT 0;
    DECLARE factures_exists INT DEFAULT 0;
    DECLARE date_envoi_mail_exists INT DEFAULT 0;
    DECLARE uniq_factures_exists INT DEFAULT 0;
    DECLARE duplicate_factures_count INT DEFAULT 0;

    SELECT COUNT(*)
      INTO notifications_exists
      FROM INFORMATION_SCHEMA.TABLES
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'notifications';

    IF notifications_exists = 0 THEN
        CREATE TABLE notifications (
            id_notification INT AUTO_INCREMENT PRIMARY KEY,
            recipient_role VARCHAR(50) NOT NULL DEFAULT 'admin',
            type VARCHAR(60) NOT NULL,
            title VARCHAR(180) NOT NULL,
            message TEXT NOT NULL,
            payload_json TEXT DEFAULT NULL,
            is_read TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            read_at DATETIME DEFAULT NULL,
            INDEX idx_notifications_recipient_read_created (
                recipient_role,
                is_read,
                created_at
            ),
            INDEX idx_notifications_created_at (created_at)
        ) ENGINE = InnoDB;
    END IF;

    SELECT COUNT(*)
      INTO disponibilites_exists
      FROM INFORMATION_SCHEMA.TABLES
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'prestataire_disponibilites';

    IF disponibilites_exists = 0 THEN
        CREATE TABLE prestataire_disponibilites (
            id_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
            id_prestataire INT NOT NULL,
            date_evenement DATE NOT NULL,
            statut VARCHAR(20) NOT NULL DEFAULT 'disponible',
            commentaire VARCHAR(255) DEFAULT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_disponibilites_prestataire FOREIGN KEY (id_prestataire) REFERENCES prestataires (id_prestataire) ON DELETE CASCADE,
            CONSTRAINT chk_disponibilites_statut CHECK (statut IN ('disponible', 'indisponible')),
            UNIQUE KEY uniq_prestataire_date (id_prestataire, date_evenement),
            INDEX idx_disponibilites_date (date_evenement)
        ) ENGINE = InnoDB;
    END IF;

    SELECT COUNT(*)
      INTO factures_exists
      FROM INFORMATION_SCHEMA.TABLES
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'factures';

    IF factures_exists = 1 THEN
        SELECT COUNT(*)
          INTO date_envoi_mail_exists
          FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = 'factures'
           AND COLUMN_NAME = 'date_envoi_mail';

        IF date_envoi_mail_exists = 0 THEN
            ALTER TABLE factures
                ADD COLUMN date_envoi_mail DATETIME DEFAULT NULL AFTER date_paiement;
        END IF;

        SELECT COUNT(*)
          INTO uniq_factures_exists
          FROM INFORMATION_SCHEMA.STATISTICS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = 'factures'
           AND INDEX_NAME = 'uniq_factures_devis';

        IF uniq_factures_exists = 0 THEN
            SELECT COUNT(*)
              INTO duplicate_factures_count
              FROM (
                    SELECT id_devis
                      FROM factures
                     GROUP BY id_devis
                    HAVING COUNT(*) > 1
                   ) AS duplicate_factures;

            IF duplicate_factures_count = 0 THEN
                ALTER TABLE factures
                    ADD UNIQUE KEY uniq_factures_devis (id_devis);
            ELSE
                SELECT 'Migration incomplete: impossible d ajouter uniq_factures_devis car des factures multiples existent pour un meme devis.' AS migration_warning;
            END IF;
        END IF;
    END IF;

    SELECT 'Migration runtime tables terminee.' AS migration_status;
END $$

DELIMITER;

CALL migrate_quickevents_runtime_tables ();

DROP PROCEDURE IF EXISTS migrate_quickevents_runtime_tables;

SELECT 'Script migration_2026_07_20_runtime_tables.sql execute.' AS migration_script_status;

SELECT TABLE_NAME, ENGINE
FROM INFORMATION_SCHEMA.TABLES
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME IN (
        'notifications',
        'prestataire_disponibilites',
        'factures'
    )
ORDER BY TABLE_NAME;