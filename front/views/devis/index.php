<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$txt = [
    'fr' => [
        'empty' => 'Aucune proposition de devis trouvee.',
        'ref' => 'Référence',
        'status' => 'Statut',
        'created' => 'Date de creation de la proposition',
        'event' => "Date de réservation de l'événement",
        'total' => 'Total',
        'view' => 'VOIR LA PROPOSITION',
    ],
    'en' => [
        'empty' => 'No quote proposal found.',
        'ref' => 'Reference',
        'status' => 'Status',
        'created' => 'Proposal creation date',
        'event' => 'Event booking date',
        'total' => 'Total',
        'view' => 'VIEW PROPOSAL',
    ],
];
$t = $txt[$lang];
?>

<?php if (empty($devisList)): ?>
    <div class="empty-box">
        <p><?= e($t['empty']) ?></p>
    </div>
<?php else: ?>
    <section class="devis-index-list">
        <?php foreach ($devisList as $devis): ?>
            <article class="devis-card">
                <div class="devis-card-top">
                    <div>
                        <span class="devis-card-label"><?= e($t['ref']) ?></span>
                        <div class="devis-card-value"><?= e($devis['reference'] ?? '') ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['status']) ?></span>
                        <div class="devis-card-value"><?= e($devis['statut'] ?? '') ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['created']) ?></span>
                        <div class="devis-card-value">
                            <?= !empty($devis['created_at']) ? date('d/m/Y', strtotime($devis['created_at'])) : '-' ?>
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['event']) ?></span>
                        <div class="devis-card-value">
                            <?= !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : '-' ?>
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['total']) ?></span>
                        <div class="devis-card-value">
                            <?= number_format((float)($devis['montant_total'] ?? 0), 2, ',', ' ') ?> €
                        </div>
                    </div>
                </div>

                <div class="devis-card-actions">
                    <a class="pill-link" href="<?= route('devis_show', ['id' => $devis['id_devis']]) . '?lang=' . $lang ?>"><?= e($t['view']) ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>