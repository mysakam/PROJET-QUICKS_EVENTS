<section class="apropos">
    <div class="admin-media-shell">
        <?php $lang = $lang ?? current_lang(); ?>
        <h2 class="titre-texte"><?= e(t('admin.dashboard.title', $lang)) ?></h2>
        <p><?= e(t('admin.dashboard.subtitle', $lang)) ?></p>

        <div class="theme-grid">
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.dashboard.medias_title', $lang)) ?></h3>
                <p class="card-text"><?= e(t('admin.dashboard.medias_text', $lang)) ?></p>
                <a class="btn" href="<?= route('admin_event_medias') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.open', $lang)) ?></a>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.dashboard.providers_title', $lang)) ?></h3>
                <p class="card-text"><?= e(t('admin.dashboard.providers_text', $lang)) ?></p>
                <a class="btn" href="<?= route('admin_prestataires_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.open', $lang)) ?></a>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.dashboard.invoices_title', $lang)) ?></h3>
                <p class="card-text"><?= e(t('admin.dashboard.invoices_text', $lang)) ?></p>
                <a class="btn" href="<?= route('admin_factures_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.open', $lang)) ?></a>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.dashboard.clients_title', $lang)) ?></h3>
                <p class="card-text"><?= e(t('admin.dashboard.clients_text', $lang)) ?></p>
                <a class="btn" href="<?= route('admin_clients_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.open', $lang)) ?></a>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.dashboard.stats_title', $lang)) ?></h3>
                <p class="card-text"><?= e(t('admin.dashboard.stats_text', $lang)) ?></p>
                <a class="btn" href="<?= route('admin_stats_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.open', $lang)) ?></a>
            </article>
        </div>
    </div>
</section>