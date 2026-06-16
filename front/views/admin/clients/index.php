<?php
$clients = $clients ?? [];
$searchQuery = $searchQuery ?? '';
$lang = $lang ?? current_lang();

$statusClass = static function (?string $status): string {
    $normalized = strtolower(trim((string) $status));

    return match ($normalized) {
        'payee', 'validee', 'valide_client' => 'status-success',
        'envoyee', 'emise' => 'status-info',
        'annulee', 'en_retard' => 'status-danger',
        'en_attente', 'en_attente_validation' => 'status-warning',
        default => 'status-neutral',
    };
};

$statusLabel = static function (?string $status) use ($lang): string {
    $normalized = trim((string) $status);
    if ($normalized === '') {
        return '-';
    }

    $key = 'admin.status.' . strtolower($normalized);
    $translated = t($key, $lang);
    if ($translated !== $key) {
        return $translated;
    }

    $label = str_replace('_', ' ', strtolower($normalized));
    return ucfirst($label);
};
?>
<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><?= e(t('admin.clients.title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="admin-alert admin-alert-success"><?= e($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_dashboard') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.dashboard.title', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_clients_create') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.clients.add', $lang)) ?></a>
        </div>

        <form class="admin-filter-form" method="GET" action="<?= route('admin_clients_index') ?>">
            <input type="hidden" name="lang" value="<?= e($lang) ?>">
            <label for="q"><?= e(t('admin.clients.search', $lang)) ?></label>
            <input id="q" name="q" type="text" value="<?= e($searchQuery) ?>" placeholder="<?= e(t('admin.clients.search_ph', $lang)) ?>">
            <button class="admin-btn" type="submit"><?= e(t('admin.clients.filter', $lang)) ?></button>
            <a class="btn" href="<?= route('admin_clients_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.clients.reset', $lang)) ?></a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= e(t('admin.clients.th_nom', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_prenom', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_email', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_phone', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_quotes', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_invoices', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_quotes_history', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_invoices_history', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_actions', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                        <tr>
                            <td colspan="10"><?= e(t('admin.clients.none', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td><?= (int) $c['id_client'] ?></td>
                                <td><?= e($c['nom']) ?></td>
                                <td><?= e($c['prenom']) ?></td>
                                <td><?= e($c['email']) ?></td>
                                <td><?= e($c['telephone'] ?? '-') ?></td>
                                <td><?= (int) ($c['devis_count'] ?? 0) ?></td>
                                <td><?= (int) ($c['factures_count'] ?? 0) ?></td>
                                <td>
                                    <?php $devisHistory = $c['devis_history'] ?? []; ?>
                                    <?php if (!empty($devisHistory)): ?>
                                        <ul class="history-list">
                                            <?php foreach ($devisHistory as $devis): ?>
                                                <?php $devisStatut = (string) ($devis['statut'] ?? ''); ?>
                                                <li>
                                                    <span><?= e((string) ($devis['reference'] ?? ('DEVIS #' . (int) ($devis['id_devis'] ?? 0)))) ?></span>
                                                    <span class="status-pill <?= e($statusClass($devisStatut)) ?>"><?= e($statusLabel($devisStatut)) ?></span>
                                                    <?php if (!empty($devis['created_at'])): ?>
                                                        <small><?= e(date('d/m/Y', strtotime((string) $devis['created_at']))) ?></small>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $facturesHistory = $c['factures_history'] ?? []; ?>
                                    <?php if (!empty($facturesHistory)): ?>
                                        <ul class="history-list">
                                            <?php foreach ($facturesHistory as $facture): ?>
                                                <?php $factureStatut = (string) ($facture['statut'] ?? ''); ?>
                                                <li>
                                                    <span><?= e((string) ($facture['reference'] ?? ('FACTURE #' . (int) ($facture['id_facture'] ?? 0)))) ?></span>
                                                    <span class="status-pill <?= e($statusClass($factureStatut)) ?>"><?= e($statusLabel($factureStatut)) ?></span>
                                                    <?php if (!empty($facture['created_at'])): ?>
                                                        <small><?= e(date('d/m/Y', strtotime((string) $facture['created_at']))) ?></small>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="admin-table-actions">
                                    <a class="admin-link" href="<?= route('admin_clients_show', ['id' => $c['id_client']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.clients.view', $lang)) ?></a>
                                    <a class="admin-link" href="<?= route('admin_clients_edit', ['id' => $c['id_client']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.clients.edit', $lang)) ?></a>
                                    <form method="POST" action="<?= route('admin_clients_delete', ['id' => $c['id_client']]) ?>?lang=<?= e($lang) ?>" onsubmit="return confirm('<?= e(t('admin.clients.delete_confirm', $lang)) ?>');">
                                        <button type="submit" class="admin-btn admin-btn-danger"><?= e(t('admin.clients.delete', $lang)) ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>