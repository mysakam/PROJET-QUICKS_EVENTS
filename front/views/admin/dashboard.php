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

        <div class="admin-notifications-shell">
            <div class="admin-notifications-head">
                <h3>Notifications recentes</h3>
                <?php $unreadNotificationsCount = (int) ($unreadNotificationsCount ?? 0); ?>
                <span class="admin-notifications-badge"><?= e((string) $unreadNotificationsCount) ?></span>
            </div>

            <?php $deleteConfirmText = $lang === 'en' ? 'Delete this notification permanently?' : 'Supprimer cette notification definitivement ?'; ?>

            <?php $notifications = $notifications ?? []; ?>
            <?php if ($notifications !== []): ?>
                <div class="admin-notifications-list">
                    <?php foreach ($notifications as $notification): ?>
                        <article class="admin-notification-card <?= !empty($notification['is_read']) ? 'is-read' : 'is-unread' ?>">
                            <a class="admin-notification-link" href="<?= route('admin_notifications_open', ['id' => (int) $notification['id_notification']]) ?>">
                                <div class="admin-notification-meta">
                                    <strong><?= e($notification['title'] ?? 'Notification') ?></strong>
                                    <span><?= e($notification['created_at'] ?? '') ?></span>
                                </div>
                                <p><?= e($notification['message'] ?? '') ?></p>
                                <?php if (!empty($notification['payload']['reference'])): ?>
                                    <small>Référence : <?= e($notification['payload']['reference']) ?></small>
                                <?php endif; ?>
                            </a>

                            <div class="admin-notification-actions">
                                <a class="btn" href="<?= route('admin_notifications_open', ['id' => (int) $notification['id_notification']]) ?>">Ouvrir le devis</a>
                                <form method="post" action="<?= route('admin_notifications_delete', ['id' => (int) $notification['id_notification']]) ?>" onsubmit="return confirm('<?= e($deleteConfirmText) ?>');">
                                    <input type="hidden" name="_csrf_token" value="<?= e(Csrf::token()) ?>">
                                    <button class="btn" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="admin-notifications-empty">Aucune notification pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>