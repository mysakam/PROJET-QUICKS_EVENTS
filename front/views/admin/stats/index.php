<?php
$kpis = $kpis ?? [];
$devisByStatut = $devisByStatut ?? [];
$topClients = $topClients ?? [];
$topPrestataires = $topPrestataires ?? [];
$topPrestations = $topPrestations ?? [];
$topCategories = $topCategories ?? [];
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><span>S</span>tatistiques</h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_dashboard') ?>">Dashboard admin</a>
        </div>

        <div class="theme-grid">
            <article class="polaroid event-polaroid">
                <h3>Clients</h3>
                <p class="card-text"><?= (int)($kpis['clients'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3>Prestataires</h3>
                <p class="card-text"><?= (int)($kpis['prestataires'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3>Devis</h3>
                <p class="card-text"><?= (int)($kpis['devis'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3>Prestations</h3>
                <p class="card-text"><?= (int)($kpis['prestations'] ?? 0) ?></p>
            </article>
            <article class="polaroid event-polaroid">
                <h3>CA total</h3>
                <p class="card-text"><?= number_format((float)($kpis['ca_total'] ?? 0), 2, ',', ' ') ?> EUR</p>
            </article>
        </div>

        <h3>Devis par statut</h3>
        <div class="admin-table-wrap">
            <table class="admin-table" style="min-width: 420px;">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devisByStatut as $row): ?>
                        <tr>
                            <td><?= e($row['statut']) ?></td>
                            <td><?= (int)$row['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h3>CA par client</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>CA</th>
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

        <h3>CA par prestataire</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Prestataire</th>
                        <th>CA</th>
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

        <h3>CA par prestation</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Prestation</th>
                        <th>CA</th>
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

        <h3>CA par categorie</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Categorie</th>
                        <th>CA</th>
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