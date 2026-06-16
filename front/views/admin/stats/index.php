<?php
$kpis = $kpis ?? [];
$devisByStatut = $devisByStatut ?? [];
$facturesByStatut = $facturesByStatut ?? [];
$topClients = $topClients ?? [];
$topPrestataires = $topPrestataires ?? [];
$topPrestations = $topPrestations ?? [];
$topCategories = $topCategories ?? [];
$lang = $lang ?? current_lang();

$statusLabel = static function (?string $status) use ($lang): string {
    $normalized = strtolower(trim((string) $status));
    if ($normalized === '') {
        return '-';
    }

    $key = 'admin.status.' . $normalized;
    $translated = t($key, $lang);
    if ($translated !== $key) {
        return $translated;
    }

    $label = str_replace('_', ' ', $normalized);
    return ucfirst($label);
};
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><?= e(t('admin.stats.title', $lang)) ?></h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_dashboard') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.dashboard.title', $lang)) ?></a>
        </div>

        <div class="theme-grid">
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.stats.kpi_clients', $lang)) ?></h3>
                <p class="card-text"><?= (int)($kpis['clients'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.stats.kpi_prestataires', $lang)) ?></h3>
                <p class="card-text"><?= (int)($kpis['prestataires'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.stats.kpi_devis', $lang)) ?></h3>
                <p class="card-text"><?= (int)($kpis['devis'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.stats.kpi_prestations', $lang)) ?></h3>
                <p class="card-text"><?= (int)($kpis['prestations'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.stats.kpi_factures', $lang)) ?></h3>
                <p class="card-text"><?= (int)($kpis['factures'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.stats.kpi_ca_total', $lang)) ?></h3>
                <p class="card-text"><?= number_format((float)($kpis['ca_total'] ?? 0), 2, ',', ' ') ?> EUR</p>
            </article>
            <article class="polaroid event-polaroid">
                <h3><?= e(t('admin.stats.kpi_ca_factures', $lang)) ?></h3>
                <p class="card-text"><?= number_format((float)($kpis['ca_factures'] ?? 0), 2, ',', ' ') ?> EUR</p>
            </article>
        </div>

        <h3><?= e(t('admin.stats.quotes_by_status', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table" style="min-width: 420px;">
                <thead>
                    <tr>
                        <th><?= e(t('admin.stats.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.stats.th_total', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devisByStatut as $row): ?>
                        <tr>
                            <td><?= e($statusLabel((string) ($row['statut'] ?? ''))) ?></td>
                            <td><?= (int)$row['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.stats.invoices_by_status', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table" style="min-width: 420px;">
                <thead>
                    <tr>
                        <th><?= e(t('admin.stats.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.stats.th_total', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facturesByStatut as $row): ?>
                        <tr>
                            <td><?= e($statusLabel((string) ($row['statut'] ?? ''))) ?></td>
                            <td><?= (int)$row['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.stats.revenue_by_client', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.stats.th_client', $lang)) ?></th>
                        <th><?= e(t('admin.stats.th_email', $lang)) ?></th>
                        <th><?= e(t('admin.stats.th_revenue', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topClients as $row): ?>
                        <tr>
                            <td><?= e(trim(($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? ''))) ?></td>
                            <td><?= e($row['email'] ?? '-') ?></td>
                            <td><?= number_format((float)$row['ca_client'], 2, ',', ' ') ?> EUR</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.stats.revenue_by_provider', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.stats.th_provider', $lang)) ?></th>
                        <th><?= e(t('admin.stats.th_revenue', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPrestataires as $row): ?>
                        <tr>
                            <td><?= e($row['nom']) ?></td>
                            <td><?= number_format((float)$row['ca_prestataire'], 2, ',', ' ') ?> EUR</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.stats.revenue_by_service', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.stats.th_service', $lang)) ?></th>
                        <th><?= e(t('admin.stats.th_revenue', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPrestations as $row): ?>
                        <tr>
                            <td><?= e($row['nom']) ?></td>
                            <td><?= number_format((float)$row['ca_prestation'], 2, ',', ' ') ?> EUR</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.stats.revenue_by_category', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.stats.th_category', $lang)) ?></th>
                        <th><?= e(t('admin.stats.th_revenue', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topCategories as $row): ?>
                        <tr>
                            <td><?= e($row['nom']) ?></td>
                            <td><?= number_format((float)$row['ca_categorie'], 2, ',', ' ') ?> EUR</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>