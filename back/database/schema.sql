CREATE DATABASE IF NOT EXISTS quickevents CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE quickevents;

DROP TABLE IF EXISTS devis_lignes;

DROP TABLE IF EXISTS devis;

DROP TABLE IF EXISTS prestations;

DROP TABLE IF EXISTS prestataires;

DROP TABLE IF EXISTS categories;

DROP TABLE IF EXISTS event_medias;

DROP TABLE IF EXISTS clients;

CREATE TABLE clients (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(30) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE categories (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE prestataires (
    id_prestataire INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    email VARCHAR(150) DEFAULT NULL UNIQUE,
    telephone VARCHAR(30) DEFAULT NULL,
    adresse VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE prestations (
    id_prestation INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    id_prestataire INT NOT NULL,
    nom VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_prestations_categories FOREIGN KEY (id_categorie) REFERENCES categories (id_categorie),
    CONSTRAINT fk_prestations_prestataires FOREIGN KEY (id_prestataire) REFERENCES prestataires (id_prestataire)
) ENGINE = InnoDB;

CREATE TABLE event_medias (
    id_media INT AUTO_INCREMENT PRIMARY KEY,
    theme_slug VARCHAR(80) NOT NULL,
    media_type ENUM('image', 'video') NOT NULL DEFAULT 'image',
    media_url VARCHAR(255) NOT NULL,
    title_fr VARCHAR(150) NOT NULL,
    title_en VARCHAR(150) NOT NULL,
    description_fr TEXT DEFAULT NULL,
    description_en TEXT DEFAULT NULL,
    position INT NOT NULL DEFAULT 1,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_medias_theme_active_pos (
        theme_slug,
        is_active,
        position
    )
) ENGINE = InnoDB;

CREATE TABLE devis (
    id_devis INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NOT NULL,
    reference VARCHAR(50) NOT NULL UNIQUE,
    statut VARCHAR(50) NOT NULL DEFAULT 'en_attente',
    date_evenement DATE DEFAULT NULL,
    message_client TEXT DEFAULT NULL,
    montant_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_devis_clients FOREIGN KEY (id_client) REFERENCES clients (id_client)
) ENGINE = InnoDB;

CREATE TABLE devis_lignes (
    id_ligne_devis INT AUTO_INCREMENT PRIMARY KEY,
    id_devis INT NOT NULL,
    id_prestation INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    montant_ligne DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_devis_lignes_devis FOREIGN KEY (id_devis) REFERENCES devis (id_devis) ON DELETE CASCADE,
    CONSTRAINT fk_devis_lignes_prestations FOREIGN KEY (id_prestation) REFERENCES prestations (id_prestation)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS event_medias (
    id_media INT AUTO_INCREMENT PRIMARY KEY,
    theme_slug VARCHAR(80) NOT NULL,
    media_type ENUM('image', 'video') NOT NULL DEFAULT 'image',
    media_url VARCHAR(255) NOT NULL,
    title_fr VARCHAR(150) NOT NULL,
    title_en VARCHAR(150) NOT NULL,
    description_fr TEXT DEFAULT NULL,
    description_en TEXT DEFAULT NULL,
    position INT NOT NULL DEFAULT 1,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_medias_theme_active_pos (
        theme_slug,
        is_active,
        position
    )
) ENGINE=InnoDB;