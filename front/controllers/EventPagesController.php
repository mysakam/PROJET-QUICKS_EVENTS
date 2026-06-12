<?php

class EventPagesController extends Controller
{
    private EventMediaModel $eventMediaModel;

    private array $pages = [
        'mariage' => [
            'title_fr' => 'Mariage',
            'title_en' => 'Wedding',
            'subtitle_fr' => 'Une atmosphère élégante pour célébrer votre grand jour.',
            'subtitle_en' => 'An elegant atmosphere to celebrate your big day.',
            'back_label_fr' => 'Retour à l’accueil',
            'back_label_en' => 'Back to home',
            'packages' => [
                [
                    'theme_fr' => 'Champêtre Élégant',
                    'theme_en' => 'Rustic Elegant',
                    'description_fr' => 'Mariage nature avec bois clair, fleurs pastel et ambiance guinguette chic.',
                    'description_en' => 'Nature-inspired wedding with light wood, pastel flowers and chic outdoor spirit.',
                    'image_src' => '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp',
                    'images' => [
                        '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp',
                        '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif',
                        '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg',
                    ],
                    'offer_items_fr' => ['Décoration complète de salle et arche florale', '100 chaises + couverts premium', 'Tente de réception et espace cocktail', 'Open bar 4h + DJ + effets spéciaux', 'Sécurité et coordination jour J'],
                    'offer_items_en' => ['Full hall decoration and floral arch', '100 chairs + premium cutlery set', 'Reception tent and cocktail area', '4h open bar + DJ + special effects', 'Security and full day coordination'],
                    'price_fr' => '4 900 EUR',
                    'price_en' => 'EUR 4,900',
                ],
                [
                    'theme_fr' => 'Black & Gold Prestige',
                    'theme_en' => 'Black & Gold Prestige',
                    'description_fr' => 'Univers luxueux noir et or, lumière tamisée et scénographie raffinée.',
                    'description_en' => 'Luxury black and gold atmosphere with refined staging and ambient lighting.',
                    'image_src' => '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif',
                    'images' => [
                        '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif',
                        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
                        '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp',
                    ],
                    'offer_items_fr' => ['Design premium noir & or', '100 places assises dressées', 'Scène mariés + piste de danse LED', 'Open bar signature + barman', 'DJ set, sécurité et maître de cérémonie'],
                    'offer_items_en' => ['Premium black & gold design setup', '100 fully dressed seats', 'Couple stage + LED dance floor', 'Signature open bar + bartender', 'DJ set, security and master of ceremony'],
                    'price_fr' => '6 700 EUR',
                    'price_en' => 'EUR 6,700',
                ],
            ],
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
            'packages' => [
                [
                    'theme_fr' => 'Color Pop Party',
                    'theme_en' => 'Color Pop Party',
                    'description_fr' => 'Anniversaire dynamique avec palette vive, candy bar et photobooth fun.',
                    'description_en' => 'Dynamic birthday setup with vivid palette, candy bar and fun photobooth.',
                    'image_src' => '/assets/css/images/image-45-768x768.jpeg',
                    'images' => [
                        '/assets/css/images/image-45-768x768.jpeg',
                        '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg',
                        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
                    ],
                    'offer_items_fr' => ['Décoration thème color pop', '100 chaises + vaisselle festive', 'Coin photo + accessoires', 'DJ + animations jeux', 'Sécurité et coordination soirée'],
                    'offer_items_en' => ['Color pop themed decoration', '100 chairs + festive tableware', 'Photo corner + props', 'DJ + game activities', 'Security and evening coordination'],
                    'price_fr' => '3 200 EUR',
                    'price_en' => 'EUR 3,200',
                ],
                [
                    'theme_fr' => 'Soirée Glam Black & White',
                    'theme_en' => 'Black & White Glam Night',
                    'description_fr' => 'Ambiance chic noir et blanc avec décoration moderne et dancefloor central.',
                    'description_en' => 'Chic black and white atmosphere with modern decor and central dancefloor.',
                    'image_src' => '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
                    'images' => [
                        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
                        '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg',
                        '/assets/css/images/image-45-768x768.jpeg',
                    ],
                    'offer_items_fr' => ['Mise en scène black & white', 'Buffet sucré/salé 100 pers', 'Open bar soft + cocktails', 'DJ set + effets lumineux', 'Agent sécurité + équipe accueil'],
                    'offer_items_en' => ['Black & white stage design', 'Sweet/savoury buffet for 100 guests', 'Soft open bar + cocktails', 'DJ set + light effects', 'Security staff + welcome team'],
                    'price_fr' => '4 450 EUR',
                    'price_en' => 'EUR 4,450',
                ],
            ],
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
            'packages' => [
                [
                    'theme_fr' => 'Tropical Sunset',
                    'theme_en' => 'Tropical Sunset',
                    'description_fr' => 'Décor exotique, lumière chaude et ambiance plage premium.',
                    'description_en' => 'Exotic decor, warm lighting and premium beach vibe.',
                    'image_src' => '/assets/css/images/Conch-Shell-Decor.webp',
                    'images' => [
                        '/assets/css/images/Conch-Shell-Decor.webp',
                        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
                        '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg',
                    ],
                    'offer_items_fr' => ['Décoration tropicale immersive', '100 assises lounge', 'Bar à cocktails exotique', 'DJ house + percussions live', 'Sécurité et staff événementiel'],
                    'offer_items_en' => ['Immersive tropical decoration', '100 lounge seats', 'Exotic cocktail bar', 'House DJ + live percussion', 'Security and event staff'],
                    'price_fr' => '4 150 EUR',
                    'price_en' => 'EUR 4,150',
                ],
                [
                    'theme_fr' => 'Neon Future Night',
                    'theme_en' => 'Neon Future Night',
                    'description_fr' => 'Scénographie LED futuriste, ambiance club et effets spéciaux.',
                    'description_en' => 'Futuristic LED staging with club atmosphere and special effects.',
                    'image_src' => '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg',
                    'images' => [
                        '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg',
                        '/assets/css/images/Conch-Shell-Decor.webp',
                        '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
                    ],
                    'offer_items_fr' => ['Décor néon + mapping lumière', '100 places et zone VIP', 'Open bar + show barman', 'DJ électro + machine fumée', 'Sécurité renforcée + coordination'],
                    'offer_items_en' => ['Neon decor + light mapping', '100 seats and VIP area', 'Open bar + bartender show', 'Electro DJ + smoke effects', 'Reinforced security + coordination'],
                    'price_fr' => '5 300 EUR',
                    'price_en' => 'EUR 5,300',
                ],
            ],
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
            'packages' => [
                [
                    'theme_fr' => 'Business Class Premium',
                    'theme_en' => 'Business Class Premium',
                    'description_fr' => 'Format corporate élégant avec équipement conférence et restauration haut de gamme.',
                    'description_en' => 'Elegant corporate format with conference setup and premium catering.',
                    'image_src' => '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg',
                    'images' => [
                        '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg',
                        '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif',
                        '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg',
                    ],
                    'offer_items_fr' => ['Mise en place salle de séminaire', '100 places assises équipées', 'Déjeuner complet + pauses café', 'Sonorisation + écran + micro', 'Hôtesse accueil et sécurité'],
                    'offer_items_en' => ['Seminar room setup', '100 equipped seated places', 'Full lunch + coffee breaks', 'Sound system + screen + microphones', 'Hostess welcome and security'],
                    'price_fr' => '3 950 EUR',
                    'price_en' => 'EUR 3,950',
                ],
                [
                    'theme_fr' => 'Green Meeting Chic',
                    'theme_en' => 'Green Meeting Chic',
                    'description_fr' => 'Séminaire éco-chic avec décoration naturelle et cuisine responsable.',
                    'description_en' => 'Eco-chic seminar with natural decoration and responsible catering.',
                    'image_src' => '/assets/css/images/Conch-Shell-Decor.webp',
                    'images' => [
                        '/assets/css/images/Conch-Shell-Decor.webp',
                        '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg',
                        '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif',
                    ],
                    'offer_items_fr' => ['Décoration végétale et mobilier bois', '100 couverts réutilisables premium', 'Buffet local et boissons detox', 'Animation musicale douce', 'Sécurité + coordination logistique'],
                    'offer_items_en' => ['Green decor with wooden furniture', '100 reusable premium place settings', 'Local buffet and detox drinks', 'Soft musical ambiance', 'Security + logistics coordination'],
                    'price_fr' => '4 300 EUR',
                    'price_en' => 'EUR 4,300',
                ],
            ],
            'polaroids' => [
                ['title_fr' => 'Accueil', 'title_en' => 'Welcome', 'media_fr' => 'Image d’accueil', 'media_en' => 'Welcome image', 'media_src' => '/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif', 'description_fr' => 'Accueil des invités, signalétique et première impression.', 'description_en' => 'Guest welcome, signage and first impression.'],
                ['title_fr' => 'Repas', 'title_en' => 'Meal', 'media_fr' => 'Image du repas', 'media_en' => 'Meal image', 'media_src' => '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg', 'description_fr' => 'Menu, service à table et ambiance conviviale.', 'description_en' => 'Menu, table service and a friendly atmosphere.'],
                ['title_fr' => 'Interventions', 'title_en' => 'Speeches', 'media_fr' => 'Image d’intervention', 'media_en' => 'Speech image', 'media_src' => '/assets/css/images/pramod-tiwari-w09m4VYrAK4-unsplash.jpg', 'description_fr' => 'Prises de parole, présentation et temps d’échange.', 'description_en' => 'Talks, presentations and discussion time.'],
                ['title_fr' => 'Organisation', 'title_en' => 'Organization', 'media_fr' => 'Image organisation', 'media_en' => 'Organization image', 'media_src' => '/assets/css/images/Conch-Shell-Decor.webp', 'description_fr' => 'Disposition des tables, timing et coordination générale.', 'description_en' => 'Table layout, timing and overall coordination.'],
            ],
        ],
    ];

    public function __construct()
    {
        $this->eventMediaModel = new EventMediaModel();
    }

    private function mapStaticPolaroids(array $staticPolaroids, string $lang): array
    {
        return array_map(static function (array $item) use ($lang): array {
            return [
                'title' => $item['title_' . $lang],
                'mediaLabel' => $item['media_' . $lang],
                'mediaType' => 'image',
                'mediaSrc' => $item['media_src'] ?? null,
                'description' => $item['description_' . $lang],
            ];
        }, $staticPolaroids);
    }

    private function mapDbPolaroids(array $dbPolaroids, string $lang): array
    {
        $defaultLabel = $lang === 'fr' ? 'Média événement' : 'Event media';

        return array_map(static function (array $item) use ($defaultLabel): array {
            return [
                'title' => $item['title'] ?: $defaultLabel,
                'mediaLabel' => $defaultLabel,
                'mediaType' => $item['media_type'] ?? 'image',
                'mediaSrc' => $item['media_url'] ?? null,
                'description' => $item['description'] ?? '',
            ];
        }, $dbPolaroids);
    }

    private function mapPackages(array $packages, string $lang): array
    {
        return array_map(static function (array $package, int $index) use ($lang): array {
            $images = $package['images'] ?? (isset($package['image_src']) ? [$package['image_src']] : []);
            return [
                'index' => $index,
                'theme' => $package['theme_' . $lang] ?? '',
                'description' => $package['description_' . $lang] ?? '',
                'imageSrc' => $images[0] ?? ($package['image_src'] ?? null),
                'images' => $images,
                'offerItems' => $package['offer_items_' . $lang] ?? [],
                'price' => $package['price_' . $lang] ?? '',
                'amount' => self::priceToFloat((string) ($package['price_fr'] ?? $package['price_en'] ?? '0')),
            ];
        }, $packages, array_keys($packages));
    }

    private static function priceToFloat(string $priceLabel): float
    {
        $digitsOnly = preg_replace('/[^0-9.,]/', '', $priceLabel) ?? '';
        $normalized = str_replace(' ', '', $digitsOnly);
        $normalized = str_replace(',', '.', $normalized);

        return (float) $normalized;
    }

    private function findPackage(string $slug, int $index): ?array
    {
        if (!isset($this->pages[$slug]['packages'][$index])) {
            return null;
        }

        return $this->pages[$slug]['packages'][$index];
    }

    public function selectPackage(string $slug, int $index): void
    {
        if (!isset($this->pages[$slug])) {
            http_response_code(404);
            echo 'Page introuvable';
            return;
        }

        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
        $package = $this->findPackage($slug, $index);

        if ($package === null) {
            http_response_code(404);
            echo 'Package introuvable';
            return;
        }

        $theme = (string) ($package['theme_' . $lang] ?? 'Package');
        $priceLabel = (string) ($package['price_' . $lang] ?? ($package['price_fr'] ?? '0 EUR'));
        $priceValue = self::priceToFloat($priceLabel);
        $packageKey = (int) (900000 + (abs(crc32($slug . '-' . $index)) % 90000));

        $cart = $_SESSION['cart'] ?? [];
        if (isset($cart[$packageKey])) {
            $cart[$packageKey]['quantity']++;
        } else {
            $cart[$packageKey] = [
                'prestation_id' => null,
                'name' => 'Package - ' . $theme,
                'category' => ($lang === 'fr') ? 'Package événementiel' : 'Event package',
                'price' => $priceValue,
                'quantity' => 1,
                'is_package' => true,
                'package_theme' => $theme,
            ];
        }

        $_SESSION['cart'] = $cart;

        $eventRequest = $_SESSION['event_request'] ?? [];
        $eventRequest['type_evenement'] = $theme;
        $eventRequest['nb_personnes'] = $eventRequest['nb_personnes'] ?? '100';
        $eventRequest['budget'] = $priceLabel;
        $_SESSION['event_request'] = $eventRequest;

        $_SESSION['selected_package'] = [
            'slug' => $slug,
            'theme' => $theme,
            'price' => $priceLabel,
        ];
        $_SESSION['success'] = ($lang === 'fr')
            ? 'Package ajouté. Panier et devis pré-remplis avec le thème choisi.'
            : 'Package added. Cart and quote were prefilled with the selected theme.';

        redirect(route('panier') . '?lang=' . $lang);
    }

    private function renderPage(string $slug): void
    {
        if (!isset($this->pages[$slug])) {
            http_response_code(404);
            echo 'Page introuvable';
            return;
        }

        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
        $page = $this->pages[$slug];

        $packages = $this->mapPackages($page['packages'] ?? [], $lang);

        $this->render('events/show', [
            'lang' => $lang,
            'page' => $page,
            'packages' => $packages,
            'slug' => $slug,
            'pageTitle' => $page['title_' . $lang],
        ], 'main');
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
