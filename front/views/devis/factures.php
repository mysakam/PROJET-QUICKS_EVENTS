<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$txt = [
    'fr' => [
        'empty' => 'Aucune facture trouvee.',
        'ref' => 'Reference facture',
        'status' => 'Statut facture',
        'date' => 'Date facture',
        'amount' => 'Montant facture (TTC)',
        'linked' => 'Proposition liee',
        'view' => 'VOIR LA PROPOSITION',
    ],
    'en' => [
        'empty' => 'No invoice found.',
        'ref' => 'Invoice reference',
        'status' => 'Invoice status',
        'date' => 'Invoice date',
        'amount' => 'Invoice amount (incl. tax)',
        'linked' => 'Linked proposal',
        'view' => 'VIEW PROPOSAL',
    ],
];
$t = $txt[$lang];
?>

<?php if (empty($factures)): ?>
    <div class="empty-box">
        <p><?= e($t['empty']) ?></p>
    </div>
<?php else: ?>
    <section class="devis-index-list">
        <?php foreach ($factures as $facture): ?>
            <article class="devis-card">
                <div class="devis-card-top">
                    <div>
                        <span class="devis-card-label"><?= e($t['ref']) ?></span>
                        <div class="devis-card-value"><?= e((string) ($facture['reference'] ?? '')) ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['status']) ?></span>
                        <div class="devis-card-value"><?= e((string) ($facture['statut'] ?? '-')) ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['date']) ?></span>
                        <div class="devis-card-value">
                            <?= !empty($facture['created_at']) ? e(date('d/m/Y', strtotime((string) $facture['created_at']))) : '-' ?>
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['amount']) ?></span>
                        <div class="devis-card-value">
                            <?= e(number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ')) ?> EUR
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label"><?= e($t['linked']) ?></span>
                        <div class="devis-card-value"><?= e((string) ($facture['devis_reference'] ?? '-')) ?></div>
                    </div>
                </div>

                <div class="devis-card-actions">
                    <?php if (!empty($facture['id_devis'])): ?>
                        <a class="pill-link" href="<?= route('devis_show', ['id' => (int) $facture['id_devis']]) . '?lang=' . $lang ?>"><?= e($t['view']) ?></a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>