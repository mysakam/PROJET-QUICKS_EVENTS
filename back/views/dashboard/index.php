<section class="card">
    <h1>Dashboard</h1>
    <p>Bienvenue <?= e(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?>.</p>

    <div class="kpi-grid">
        <article><strong><?= (int) ($stats['clients'] ?? 0) ?></strong><span>Clients</span></article>
        <article><strong><?= (int) ($stats['devis'] ?? 0) ?></strong><span>Devis</span></article>
        <article><strong><?= (int) ($stats['prestations'] ?? 0) ?></strong><span>Prestations</span></article>
        <article><strong><?= (int) ($stats['factures'] ?? 0) ?></strong><span>Factures</span></article>
    </div>
</section>