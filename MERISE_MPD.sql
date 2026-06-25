-- ============================================================================
-- MERISE - Modèle Physique de Données (MPD) - MySQL/InnoDB
-- Projet: QuickEvents
-- Version: 1.0
-- ============================================================================
-- Améliorations par rapport au schéma original:
-- 1. Suppression du doublon event_medias
-- 2. Ajout de contraintes CHECK
-- 3. Ajout de contrainte UNIQUE sur (id_devis, id_prestation) dans devis_lignes
-- 4. Option: UNIQUE(id_devis) dans factures si règle métier = 1 facture/devis
-- 5. Amélioration des indexes pour les performances
-- ============================================================================

CREATE DATABASE IF NOT EXISTS quickevents CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE quickevents;

-- Drop tables in correct order (respecter les dépendances FK)
DROP TABLE IF EXISTS factures;

DROP TABLE IF EXISTS devis_lignes;

DROP TABLE IF EXISTS devis;

DROP TABLE IF EXISTS event_medias;

DROP TABLE IF EXISTS prestations;

DROP TABLE IF EXISTS prestataires;

DROP TABLE IF EXISTS categories;

DROP TABLE IF EXISTS clients;

-- ============================================================================
-- TABLE: clients
-- ============================================================================
CREATE TABLE clients (
    id_client INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique du client',
    nom VARCHAR(100) NOT NULL COMMENT 'Nom du client',
    prenom VARCHAR(100) NOT NULL COMMENT 'Prénom du client',
    email VARCHAR(150) NOT NULL UNIQUE COMMENT 'Email unique du client',
    mot_de_passe VARCHAR(255) NOT NULL COMMENT 'Mot de passe chiffré',
    telephone VARCHAR(30) DEFAULT NULL COMMENT 'Téléphone (optionnel)',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    INDEX idx_clients_email (email),
    INDEX idx_clients_created_at (created_at)
) ENGINE = InnoDB COMMENT = 'Table des clients';

-- ============================================================================
-- TABLE: categories
-- ============================================================================
CREATE TABLE categories (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique',
    nom VARCHAR(100) NOT NULL COMMENT 'Nom de la catégorie',
    slug VARCHAR(120) NOT NULL UNIQUE COMMENT 'Slug unique pour URL',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    INDEX idx_categories_slug (slug)
) ENGINE = InnoDB COMMENT = 'Table des catégories de prestations';

-- ============================================================================
-- TABLE: prestataires
-- ============================================================================
CREATE TABLE prestataires (
    id_prestataire INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique',
    nom VARCHAR(150) NOT NULL COMMENT 'Nom du prestataire',
    email VARCHAR(150) DEFAULT NULL UNIQUE COMMENT 'Email unique (nullable)',
    telephone VARCHAR(30) DEFAULT NULL COMMENT 'Téléphone (optionnel)',
    adresse VARCHAR(255) DEFAULT NULL COMMENT 'Adresse (optionnel)',
    description TEXT DEFAULT NULL COMMENT 'Description (optionnel)',
    iban VARCHAR(34) DEFAULT NULL COMMENT 'IBAN pour virement',
    bic VARCHAR(20) DEFAULT NULL COMMENT 'BIC pour virement',
    banque_nom VARCHAR(150) DEFAULT NULL COMMENT 'Nom de la banque',
    titulaire_compte VARCHAR(150) DEFAULT NULL COMMENT 'Titulaire du compte',
    note_sur_10 DECIMAL(3, 1) DEFAULT NULL COMMENT 'Note entre 0 et 10',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    INDEX idx_prestataires_email (email),
    INDEX idx_prestataires_nom (nom),
    CONSTRAINT chk_note_range CHECK (
        note_sur_10 IS NULL
        OR (
            note_sur_10 >= 0
            AND note_sur_10 <= 10
        )
    )
) ENGINE = InnoDB COMMENT = 'Table des prestataires';

-- ============================================================================
-- TABLE: prestations
-- ============================================================================
CREATE TABLE prestations (
    id_prestation INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique',
    id_categorie INT NOT NULL COMMENT 'FK vers catégorie',
    id_prestataire INT NOT NULL COMMENT 'FK vers prestataire',
    nom VARCHAR(150) NOT NULL COMMENT 'Nom de la prestation',
    description TEXT DEFAULT NULL COMMENT 'Description (optionnel)',
    prix_unitaire DECIMAL(10, 2) NOT NULL COMMENT 'Prix unitaire TTC',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Prestation active (0/1)',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    CONSTRAINT fk_prestations_categories FOREIGN KEY (id_categorie) REFERENCES categories (id_categorie) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_prestations_prestataires FOREIGN KEY (id_prestataire) REFERENCES prestataires (id_prestataire) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_prix_unitaire CHECK (prix_unitaire >= 0),
    INDEX idx_prestations_categorie (id_categorie),
    INDEX idx_prestations_prestataire (id_prestataire),
    INDEX idx_prestations_active (is_active),
    INDEX idx_prestations_created_at (created_at)
) ENGINE = InnoDB COMMENT = 'Table des prestations (offre)';

-- ============================================================================
-- TABLE: event_medias
-- ============================================================================
CREATE TABLE event_medias (
    id_media INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique',
    theme_slug VARCHAR(80) NOT NULL COMMENT 'Slug du thème',
    media_type ENUM('image', 'video') NOT NULL DEFAULT 'image' COMMENT 'Type de média',
    media_url VARCHAR(255) NOT NULL COMMENT 'URL du média',
    title_fr VARCHAR(150) NOT NULL COMMENT 'Titre en français',
    title_en VARCHAR(150) NOT NULL COMMENT 'Titre en anglais',
    description_fr TEXT DEFAULT NULL COMMENT 'Description FR (optionnel)',
    description_en TEXT DEFAULT NULL COMMENT 'Description EN (optionnel)',
    position INT NOT NULL DEFAULT 1 COMMENT 'Position d''affichage',
    is_active TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Actif (0/1)',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Dernière modification',
    INDEX idx_event_medias_theme_active_pos (
        theme_slug,
        is_active,
        position
    ),
    INDEX idx_event_medias_active (is_active)
) ENGINE = InnoDB COMMENT = 'Médias (images, vidéos) pour thèmes événementiels';

-- ============================================================================
-- TABLE: devis
-- ============================================================================
CREATE TABLE devis (
    id_devis INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique',
    id_client INT NOT NULL COMMENT 'FK vers client',
    reference VARCHAR(50) NOT NULL UNIQUE COMMENT 'Numéro de devis (unique)',
    statut VARCHAR(50) NOT NULL DEFAULT 'en_attente' COMMENT 'Statut: en_attente, accepte, refuse, facture',
    date_evenement DATE DEFAULT NULL COMMENT 'Date prévue de l''événement',
    message_client TEXT DEFAULT NULL COMMENT 'Message/demandes du client',
    montant_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Montant total TTC',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    CONSTRAINT fk_devis_clients FOREIGN KEY (id_client) REFERENCES clients (id_client) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_montant_total CHECK (montant_total >= 0),
    INDEX idx_devis_client (id_client),
    INDEX idx_devis_reference (reference),
    INDEX idx_devis_statut (statut),
    INDEX idx_devis_created_at (created_at)
) ENGINE = InnoDB COMMENT = 'Table des devis';

-- ============================================================================
-- TABLE: devis_lignes (Association porteuse)
-- ============================================================================
CREATE TABLE devis_lignes (
    id_ligne_devis INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique',
    id_devis INT NOT NULL COMMENT 'FK vers devis',
    id_prestation INT NOT NULL COMMENT 'FK vers prestation',
    quantite INT NOT NULL DEFAULT 1 COMMENT 'Quantité commandée',
    prix_unitaire DECIMAL(10, 2) NOT NULL COMMENT 'Prix unitaire au moment du devis',
    montant_ligne DECIMAL(10, 2) NOT NULL COMMENT 'Montant = quantité × prix_unitaire',
    CONSTRAINT fk_devis_lignes_devis FOREIGN KEY (id_devis) REFERENCES devis (id_devis) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_devis_lignes_prestations FOREIGN KEY (id_prestation) REFERENCES prestations (id_prestation) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_quantite CHECK (quantite > 0),
    CONSTRAINT chk_prix_unitaire_ligne CHECK (prix_unitaire >= 0),
    CONSTRAINT chk_montant_ligne CHECK (montant_ligne >= 0),
    CONSTRAINT uk_devis_prestation UNIQUE KEY (id_devis, id_prestation) COMMENT 'Une prestation max par ligne de devis',
    INDEX idx_devis_lignes_devis (id_devis),
    INDEX idx_devis_lignes_prestation (id_prestation)
) ENGINE = InnoDB COMMENT = 'Lignes de devis (association N:N entre devis et prestations)';

-- ============================================================================
-- TABLE: factures
-- ============================================================================
CREATE TABLE factures (
    id_facture INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identifiant unique',
    id_devis INT NOT NULL COMMENT 'FK vers devis',
    reference VARCHAR(50) NOT NULL UNIQUE COMMENT 'Numéro de facture (unique)',
    statut VARCHAR(50) NOT NULL DEFAULT 'emise' COMMENT 'Statut: emise, envoyee, payee, avoir',
    montant_ttc DECIMAL(10, 2) NOT NULL DEFAULT 0.00 COMMENT 'Montant total TTC',
    date_emission DATE DEFAULT NULL COMMENT 'Date d''émission',
    date_echeance DATE DEFAULT NULL COMMENT 'Date limite de paiement',
    date_paiement DATE DEFAULT NULL COMMENT 'Date de paiement (si payée)',
    date_envoi_mail DATETIME DEFAULT NULL COMMENT 'Date d''envoi par email',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    CONSTRAINT fk_factures_devis FOREIGN KEY (id_devis) REFERENCES devis (id_devis) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_montant_ttc CHECK (montant_ttc >= 0),
    CONSTRAINT uk_factures_devis UNIQUE KEY (id_devis) COMMENT 'Une facture max par devis (à adapter selon métier)',
    INDEX idx_factures_devis (id_devis),
    INDEX idx_factures_reference (reference),
    INDEX idx_factures_statut (statut),
    INDEX idx_factures_created_at (created_at),
    INDEX idx_factures_date_paiement (date_paiement)
) ENGINE = InnoDB COMMENT = 'Table des factures';

-- ============================================================================
-- FIN DU SCRIPT
-- ============================================================================