-- Partie 1 : Vider les tables (à exécuter seule si besoin)
USE quickevents;

SET NAMES utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE devis_lignes;

TRUNCATE TABLE devis;

TRUNCATE TABLE prestations;

TRUNCATE TABLE prestataires;

TRUNCATE TABLE categories;

TRUNCATE TABLE clients;

SET FOREIGN_KEY_CHECKS = 1;

-- Partie 2 : Insérer les données (à exécuter après la partie 1)
INSERT INTO
    clients (
        id_client,
        nom,
        prenom,
        email,
        mot_de_passe,
        telephone
    )
VALUES (
        1,
        'Akam',
        'Samy',
        'samy@test.com',
        '$2y$10$YpCrtfzQNk89vQHd66qiZ.c0tPylxdBFmt1Car1Si4w1.G2DYMNyi',
        '0600000001'
    );

INSERT INTO
    categories (id_categorie, nom, slug)
VALUES (1, 'Décoration', 'decoration'),
    (2, 'Mobilier', 'mobilier'),
    (3, 'Traiteur', 'traiteur'),
    (4, 'Sécurité', 'securite'),
    (
        5,
        'Son et lumière',
        'son-lumiere'
    );

INSERT INTO
    prestataires (
        id_prestataire,
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
    )
VALUES (
        1,
        'Eden Decor',
        'eden.decor@test.com',
        '0600000002',
        '12 rue des Fleurs, Marseille',
        'Spécialiste décoration événementielle',
        'FR7611111000000000000000101',
        'PSSTFRPPMAR',
        'Banque Provence',
        'Eden Decor SARL',
        8.6
    ),
    (
        2,
        'Mobilier Pro Events',
        'mobilier.pro@test.com',
        '0600000003',
        '8 avenue Prado, Marseille',
        'Location de mobilier pour événements',
        'FR7611111000000000000000102',
        'PSSTFRPPMAR',
        'Banque Provence',
        'Mobilier Pro Events SAS',
        8.1
    ),
    (
        3,
        'Saveurs Prestige',
        'saveurs.prestige@test.com',
        '0600000004',
        '25 quai du Port, Marseille',
        'Service traiteur premium',
        'FR7611111000000000000000103',
        'PSSTFRPPMAR',
        'Banque Provence',
        'Saveurs Prestige SARL',
        9.2
    ),
    (
        4,
        'Secure Night',
        'secure.night@test.com',
        '0600000005',
        '4 boulevard National, Marseille',
        'Sécurité pour soirées et événements',
        'FR7611111000000000000000104',
        'PSSTFRPPMAR',
        'Banque Provence',
        'Secure Night SAS',
        7.8
    ),
    (
        5,
        'Light & Sound Concept',
        'light.sound@test.com',
        '0600000006',
        '18 rue Sainte, Marseille',
        'Sonorisation et éclairage professionnel',
        'FR7611111000000000000000105',
        'PSSTFRPPMAR',
        'Banque Provence',
        'Light & Sound Concept SARL',
        8.9
    );

INSERT INTO
    prestations (
        id_prestation,
        id_categorie,
        id_prestataire,
        nom,
        description,
        prix_unitaire,
        is_active
    )
VALUES (
        1,
        1,
        1,
        'Pack décoration mariage',
        'Décoration complète de salle pour mariage',
        1200.00,
        1
    ),
    (
        2,
        1,
        1,
        'Arche florale',
        'Arche décorative pour cérémonie ou entrée',
        350.00,
        1
    ),
    (
        3,
        2,
        2,
        'Location tables et chaises',
        'Pack mobilier pour 100 invités',
        600.00,
        1
    ),
    (
        4,
        2,
        2,
        'Location lounge premium',
        'Canapés, tables basses et poufs pour espace VIP',
        900.00,
        1
    ),
    (
        5,
        3,
        3,
        'Buffet cocktail 50 personnes',
        'Cocktail salé sucré avec boissons soft',
        1500.00,
        1
    ),
    (
        6,
        3,
        3,
        'Service dîner assis 100 personnes',
        'Prestation traiteur complète avec service',
        4200.00,
        1
    ),
    (
        7,
        4,
        4,
        'Agent de sécurité événement',
        'Présence d’un agent pour 8 heures',
        220.00,
        1
    ),
    (
        8,
        5,
        5,
        'Pack sonorisation DJ',
        'Enceintes, micros et console DJ',
        800.00,
        1
    ),
    (
        9,
        5,
        5,
        'Pack éclairage scène',
        'Projecteurs et jeux de lumière pour scène',
        950.00,
        1
    );

INSERT INTO
    event_medias (
        theme_slug,
        media_type,
        media_url,
        title_fr,
        title_en,
        description_fr,
        description_en,
        position,
        is_active
    )
VALUES (
        'catalogue-category-1',
        'image',
        '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp',
        'Catégorie Décoration',
        'Decoration category',
        'Visuel de la catégorie décoration.',
        'Visual for decoration category.',
        1,
        1
    ),
    (
        'catalogue-category-2',
        'image',
        '/assets/css/images/image-45-768x768.jpeg',
        'Catégorie Mobilier',
        'Furniture category',
        'Visuel de la catégorie mobilier.',
        'Visual for furniture category.',
        1,
        1
    ),
    (
        'catalogue-category-3',
        'image',
        '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif',
        'Catégorie Traiteur',
        'Catering category',
        'Visuel de la catégorie traiteur.',
        'Visual for catering category.',
        1,
        1
    ),
    (
        'catalogue-category-4',
        'image',
        '/assets/css/images/Conch-Shell-Decor.webp',
        'Catégorie Sécurité',
        'Security category',
        'Visuel de la catégorie sécurité.',
        'Visual for security category.',
        1,
        1
    ),
    (
        'catalogue-category-5',
        'image',
        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
        'Catégorie Son et lumière',
        'Sound and lighting category',
        'Visuel de la catégorie son et lumière.',
        'Visual for sound and lighting category.',
        1,
        1
    );

INSERT INTO
    event_medias (
        theme_slug,
        media_type,
        media_url,
        title_fr,
        title_en,
        description_fr,
        description_en,
        position,
        is_active
    )
VALUES (
        'catalogue-prestation-1',
        'image',
        '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp',
        'Pack décoration mariage',
        'Wedding decoration pack',
        'Visuel du pack décoration mariage.',
        'Visual for wedding decoration pack.',
        1,
        1
    ),
    (
        'catalogue-prestation-2',
        'image',
        '/assets/css/images/Conch-Shell-Decor.webp',
        'Arche florale',
        'Floral arch',
        'Visuel arche florale.',
        'Visual floral arch.',
        1,
        1
    ),
    (
        'catalogue-prestation-3',
        'image',
        '/assets/css/images/image-45-768x768.jpeg',
        'Location tables et chaises',
        'Tables and chairs rental',
        'Visuel mobilier tables et chaises.',
        'Visual tables and chairs.',
        1,
        1
    ),
    (
        'catalogue-prestation-4',
        'image',
        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
        'Location lounge premium',
        'Premium lounge rental',
        'Visuel lounge premium.',
        'Visual premium lounge.',
        1,
        1
    ),
    (
        'catalogue-prestation-5',
        'image',
        '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif',
        'Buffet cocktail 50 personnes',
        'Cocktail buffet for 50 guests',
        'Visuel buffet cocktail.',
        'Visual cocktail buffet.',
        1,
        1
    ),
    (
        'catalogue-prestation-6',
        'image',
        '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg',
        'Service dîner assis 100 personnes',
        'Seated dinner service for 100 guests',
        'Visuel service dîner assis.',
        'Visual seated dinner service.',
        1,
        1
    ),
    (
        'catalogue-prestation-7',
        'image',
        '/assets/css/images/image-45-768x768.jpeg',
        'Agent de sécurité événement',
        'Event security guard',
        'Visuel prestation sécurité.',
        'Visual security service.',
        1,
        1
    ),
    (
        'catalogue-prestation-8',
        'image',
        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
        'Pack sonorisation DJ',
        'DJ sound system pack',
        'Visuel pack sonorisation DJ.',
        'Visual DJ sound pack.',
        1,
        1
    ),
    (
        'catalogue-prestation-9',
        'image',
        '/assets/css/images/Conch-Shell-Decor.webp',
        'Pack éclairage scène',
        'Stage lighting pack',
        'Visuel pack éclairage scène.',
        'Visual stage lighting pack.',
        1,
        1
    );