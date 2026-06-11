<?php
$langQuery = '?lang=' . ($lang ?? 'fr');
$homeUrl = route('home') . $langQuery;
$catalogueUrl = route('catalogues') . $langQuery;
?>

<section class="banniere theme-banner" id="banniere">
    <div class="contenu">
        <h1><?= e($page['title_' . ($lang ?? 'fr')] ?? '') ?></h1>
        <p><?= e($page['subtitle_' . ($lang ?? 'fr')] ?? '') ?></p>
        <div class="theme-hero-actions">
            <a href="<?= $catalogueUrl ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'Voir le catalogue' : 'Browse catalogue' ?></a>
            <a href="<?= $homeUrl ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'Retour à l’accueil' : 'Back to home' ?></a>
        </div>
    </div>
</section>

<section class="apropos" id="apropos">
    <?php if (!empty($packages)): ?>
        <div class="event-packages">
            <h2 class="titre-texte event-packages-title">
                <?= ($lang ?? 'fr') === 'fr' ? 'Packages recommandés pour cet événement' : 'Recommended packages for this event' ?>
            </h2>

            <div class="event-packages-grid">
                <?php foreach ($packages as $package): ?>
                    <article class="event-package-card">
                        <div class="event-package-media">
                            <?php if (!empty($package['imageSrc'])): ?>
                                <button
                                    class="event-package-image-btn"
                                    type="button"
                                    data-package-image
                                    data-image-src="<?= e($package['imageSrc']) ?>"
                                    data-image-alt="<?= e($package['theme']) ?>"
                                    aria-label="<?= ($lang ?? 'fr') === 'fr' ? 'Voir l\'image en grand' : 'Open full image' ?>">
                                    <img src="<?= e($package['imageSrc']) ?>" alt="<?= e($package['theme']) ?>">
                                </button>
                            <?php else: ?>
                                <span><?= ($lang ?? 'fr') === 'fr' ? 'Image du package' : 'Package image' ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="event-package-content">
                            <h3 class="event-package-theme"><?= e($package['theme']) ?></h3>
                            <p class="event-package-description"><?= e($package['description']) ?></p>

                            <h4 class="event-package-subtitle">
                                <?= ($lang ?? 'fr') === 'fr' ? 'Contenu de l’offre' : 'Offer content' ?>
                            </h4>
                            <ul class="event-package-list">
                                <?php foreach (($package['offerItems'] ?? []) as $item): ?>
                                    <li><?= e($item) ?></li>
                                <?php endforeach; ?>
                            </ul>

                            <p class="event-package-price">
                                <?= ($lang ?? 'fr') === 'fr' ? 'Prix global: ' : 'Global price: ' ?>
                                <strong><?= e($package['price']) ?></strong>
                            </p>

                            <a
                                class="btn"
                                href="<?= route('event_package_select', ['slug' => $slug, 'index' => (int) ($package['index'] ?? 0)]) . $langQuery ?>">
                                <?= ($lang ?? 'fr') === 'fr' ? 'Prendre ce package' : 'Take this package' ?>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="event-lightbox" data-event-lightbox hidden>
        <div class="event-lightbox-backdrop" data-event-lightbox-close></div>
        <div class="event-lightbox-content" role="dialog" aria-modal="true" aria-label="Image package">
            <button class="event-lightbox-close" type="button" data-event-lightbox-close aria-label="Close">×</button>
            <img src="" alt="" data-event-lightbox-image>
        </div>
    </div>

    <div class="action theme-actions">
        <a href="<?= $homeUrl ?>" class="btn theme-back-btn"><?= e($page['back_label_' . ($lang ?? 'fr')] ?? (($lang ?? 'fr') === 'fr' ? 'Retour à l\'accueil' : 'Back to home')) ?></a>
    </div>
</section>