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
        '$2y$10$ZZgidpiGw91yBWtrTFZAyeylNrgAN/RSCZZGRczpLzjE4fbAS9u2e',
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
        description
    )
VALUES (
        1,
        'Eden Decor',
        'eden.decor@test.com',
        '0600000002',
        '12 rue des Fleurs, Marseille',
        'Spécialiste décoration événementielle'
    ),
    (
        2,
        'Mobilier Pro Events',
        'mobilier.pro@test.com',
        '0600000003',
        '8 avenue Prado, Marseille',
        'Location de mobilier pour événements'
    ),
    (
        3,
        'Saveurs Prestige',
        'saveurs.prestige@test.com',
        '0600000004',
        '25 quai du Port, Marseille',
        'Service traiteur premium'
    ),
    (
        4,
        'Secure Night',
        'secure.night@test.com',
        '0600000005',
        '4 boulevard National, Marseille',
        'Sécurité pour soirées et événements'
    ),
    (
        5,
        'Light & Sound Concept',
        'light.sound@test.com',
        '0600000006',
        '18 rue Sainte, Marseille',
        'Sonorisation et éclairage professionnel'
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