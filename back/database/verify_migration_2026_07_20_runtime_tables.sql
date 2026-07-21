USE quickevents;

SELECT 'Verification migration runtime tables' AS check_name;

SELECT
    TABLE_NAME,
    CASE
        WHEN COUNT(*) > 0 THEN 'OK'
        ELSE 'MISSING'
    END AS status
FROM INFORMATION_SCHEMA.TABLES
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME IN (
        'notifications',
        'prestataire_disponibilites',
        'factures'
    )
GROUP BY
    TABLE_NAME
ORDER BY TABLE_NAME;

SELECT
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'factures'
    AND COLUMN_NAME = 'date_envoi_mail';

SELECT
    INDEX_NAME,
    NON_UNIQUE,
    COLUMN_NAME,
    SEQ_IN_INDEX
FROM INFORMATION_SCHEMA.STATISTICS
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'factures'
    AND INDEX_NAME = 'uniq_factures_devis'
ORDER BY SEQ_IN_INDEX;

SELECT
    INDEX_NAME,
    NON_UNIQUE,
    COLUMN_NAME,
    SEQ_IN_INDEX
FROM INFORMATION_SCHEMA.STATISTICS
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'prestataire_disponibilites'
    AND INDEX_NAME = 'uniq_prestataire_date'
ORDER BY SEQ_IN_INDEX;

SELECT
    CONSTRAINT_NAME,
    TABLE_NAME,
    REFERENCED_TABLE_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'prestataire_disponibilites'
    AND CONSTRAINT_NAME = 'fk_disponibilites_prestataire';

SELECT id_devis, COUNT(*) AS facture_count
FROM factures
GROUP BY
    id_devis
HAVING
    COUNT(*) > 1;