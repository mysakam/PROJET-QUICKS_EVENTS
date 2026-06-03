<?php

class EventPagesController extends Controller
{
    private array $pages = [
        'mariage' => [
            'title_fr' => 'Mariage',
            'title_en' => 'Wedding',
            'subtitle_fr' => 'Une atmosphère élégante pour célébrer votre grand jour.',
            'subtitle_en' => 'An elegant atmosphere to celebrate your big day.',
            'back_label_fr' => 'Retour à l’accueil',
            'back_label_en' => 'Back to home',
            'polaroids' => [
                ['title_fr' => 'Décoration', 'title_en' => 'Decoration', 'media_fr' => 'Image de décoration', 'media_en' => 'Decoration image', 'media_src' => '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp', 'description_fr' => 'Ambiance florale, tables élégantes et mise en scène raffinée.', 'description_en' => 'Floral atmosphere, elegant tables and refined staging.'],
                ['title_fr' => 'Prestations', 'title_en' => 'Services', 'media_fr' => 'Image de service', 'media_en' => 'Service image', 'media_src' => '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif', 'description_fr' => 'Traiteur, DJ, photographe et coordination de cérémonie.', 'description_en' => 'Catering, DJ, photographer and ceremony coordination.'],
                ['title_fr' => 'Moments forts', 'title_en' => 'Highlights', 'media_fr' => 'Photo souvenir', 'media_en' => 'Memory photo', 'media_src' => '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg', 'description_fr' => 'Entrée des mariés, échange des vœux et première danse.', 'description_en' => 'Bride and groom entrance, vows exchange and first dance.'],
                ['title_fr' => 'Détails pratiques', 'title_en' => 'Practical details', 'media_fr' => 'Image d’inspiration', 'media_en' => 'Inspiration image', 'media_src' => '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg', 'description_fr' => 'Capacité, planning et notes utiles pour préparer l’événement.', 'description_en' => 'Capacity, timeline and useful notes to prepare the event.'],
            ],
        ],
        'anniversaire' => [
            'title_fr' => 'Anniversaire',
            'title_en' => 'Birthday',
            'subtitle_fr' => 'Des idées festives pour une célébration inoubliable.',
            'subtitle_en' => 'Festive ideas for an unforgettable celebration.',
            'back_label_fr' => 'Retour à l’accueil',
            'back_label_en' => 'Back to home',
            'polaroids' => [
                ['title_fr' => 'Thème', 'title_en' => 'Theme', 'media_fr' => 'Photo de thème', 'media_en' => 'Theme photo', 'media_src' => '/assets/css/images/image-45-768x768.jpeg', 'description_fr' => 'Univers coloré, ambiance personnalisée et déco ludique.', 'description_en' => 'Colorful universe, custom atmosphere and playful decor.'],
                ['title_fr' => 'Animations', 'title_en' => 'Entertainment', 'media_fr' => 'Image d’animation', 'media_en' => 'Entertainment image', 'media_src' => '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg', 'description_fr' => 'Jeux, musique, surprises et activités pour tous les invités.', 'description_en' => 'Games, music, surprises and activities for all guests.'],
                ['title_fr' => 'Gâteau & buffet', 'title_en' => 'Cake & buffet', 'media_fr' => 'Image gourmande', 'media_en' => 'Food image', 'media_src' => '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg', 'description_fr' => 'Gâteau personnalisé, buffet sucré/salé et boissons festives.', 'description_en' => 'Custom cake, sweet/savoury buffet and festive drinks.'],
                ['title_fr' => 'Souvenirs', 'title_en' => 'Memories', 'media_fr' => 'Photo souvenir', 'media_en' => 'Memory photo', 'media_src' => '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg', 'description_fr' => 'Espace photo, livre d’or et cadeaux pour les invités.', 'description_en' => 'Photo area, guest book and gifts for guests.'],
            ],
        ],
        'soiree-theme' => [
            'title_fr' => 'Soirée à thème',
            'title_en' => 'Theme party',
            'subtitle_fr' => 'Une scénographie immersive pour une soirée originale.',
            'subtitle_en' => 'An immersive setup for a unique themed night.',
            'back_label_fr' => 'Retour à l’accueil',
            'back_label_en' => 'Back to home',
            'polaroids' => [
                ['title_fr' => 'Ambiance', 'title_en' => 'Mood', 'media_fr' => 'Photo d’ambiance', 'media_en' => 'Mood photo', 'media_src' => '/assets/css/images/Conch-Shell-Decor.webp', 'description_fr' => 'Lumières, couleurs et décor pour une immersion totale.', 'description_en' => 'Lights, colors and decor for full immersion.'],
                ['title_fr' => 'Dress code', 'title_en' => 'Dress code', 'media_fr' => 'Image dress code', 'media_en' => 'Dress code image', 'media_src' => '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg', 'description_fr' => 'Idées de tenues et accessoires pour rester dans le thème.', 'description_en' => 'Outfit and accessory ideas to stay on theme.'],
                ['title_fr' => 'Animation', 'title_en' => 'Entertainment', 'media_fr' => 'Image d’animation', 'media_en' => 'Entertainment image', 'media_src' => '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg', 'description_fr' => 'DJ, jeux de lumière et moments forts de la soirée.', 'description_en' => 'DJ, lighting and key moments from the evening.'],
                ['title_fr' => 'Scène finale', 'title_en' => 'Final scene', 'media_fr' => 'Image finale', 'media_en' => 'Final image', 'media_src' => '/assets/css/images/image-45-768x768.jpeg', 'description_fr' => 'Photo de groupe, arrivée surprise ou moment signature.', 'description_en' => 'Group photo, surprise entrance or signature moment.'],
            ],
        ],
        'repas-seminaire' => [
            'title_fr' => 'Repas séminaire',
            'title_en' => 'Seminar meal',
            'subtitle_fr' => 'Un cadre professionnel et chaleureux pour vos rencontres.',
            'subtitle_en' => 'A professional and warm setting for your meetings.',
            'back_label_fr' => 'Retour à l’accueil',
            'back_label_en' => 'Back to home',
            'polaroids' => [
                ['title_fr' => 'Accueil', 'title_en' => 'Welcome', 'media_fr' => 'Image d’accueil', 'media_en' => 'Welcome image', 'media_src' => '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif', 'description_fr' => 'Accueil des invités, signalétique et première impression.', 'description_en' => 'Guest welcome, signage and first impression.'],
                ['title_fr' => 'Repas', 'title_en' => 'Meal', 'media_fr' => 'Image du repas', 'media_en' => 'Meal image', 'media_src' => '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg', 'description_fr' => 'Menu, service à table et ambiance conviviale.', 'description_en' => 'Menu, table service and a friendly atmosphere.'],
                ['title_fr' => 'Interventions', 'title_en' => 'Speeches', 'media_fr' => 'Image d’intervention', 'media_en' => 'Speech image', 'media_src' => '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg', 'description_fr' => 'Prises de parole, présentation et temps d’échange.', 'description_en' => 'Talks, presentations and discussion time.'],
                ['title_fr' => 'Organisation', 'title_en' => 'Organization', 'media_fr' => 'Image organisation', 'media_en' => 'Organization image', 'media_src' => '/assets/css/images/Conch-Shell-Decor.webp', 'description_fr' => 'Disposition des tables, timing et coordination générale.', 'description_en' => 'Table layout, timing and overall coordination.'],
            ],
        ],
    ];
       //
    private function renderPage(string $slug): void
    {
        if (!isset($this->pages[$slug])) {
            http_response_code(404);
            echo 'Page introuvable';
            return;
        }

        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
        $page = $this->pages[$slug];

        $polaroids = array_map(static function (array $item) use ($lang): array {
            return [
                'title' => $item['title_' . $lang],
                'mediaLabel' => $item['media_' . $lang],
                'mediaSrc' => $item['media_src'] ?? null,
                'description' => $item['description_' . $lang],
            ];
        }, $page['polaroids']);

        $this->render('events/show', [
            'lang' => $lang,
            'page' => $page,
            'polaroids' => $polaroids,
            'slug' => $slug,
            'pageTitle' => $page['title_' . $lang],
        ], 'none');
    }

    public function mariage(): void
    {
        $this->renderPage('mariage');
    }

    public function anniversaire(): void
    {
        $this->renderPage('anniversaire');
    }

    public function soireeTheme(): void
    {
        $this->renderPage('soiree-theme');
    }

    public function repasSeminaire(): void
    {
        $this->renderPage('repas-seminaire');
    }
}