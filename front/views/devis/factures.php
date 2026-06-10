<?php if (empty($factures)): ?>
    <div class="empty-box">
        <p>Aucune facture trouvee.</p>
    </div>
<?php else: ?>
    <section class="devis-index-list">
        <?php foreach ($factures as $facture): ?>
            <article class="devis-card">
                <div class="devis-card-top">
                    <div>
                        <span class="devis-card-label">Reference facture</span>
                        <div class="devis-card-value"><?= e((string) ($facture['reference'] ?? '')) ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label">Statut facture</span>
                        <div class="devis-card-value"><?= e((string) ($facture['statut'] ?? '-')) ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label">Date facture</span>
                        <div class="devis-card-value">
                            <?= !empty($facture['created_at']) ? e(date('d/m/Y', strtotime((string) $facture['created_at']))) : '-' ?>
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label">Montant facture (TTC)</span>
                        <div class="devis-card-value">
                            <?= e(number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ')) ?> EUR
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label">Devis lie</span>
                        <div class="devis-card-value"><?= e((string) ($facture['devis_reference'] ?? '-')) ?></div>
                    </div>
                </div>

                <div class="devis-card-actions">
                    <?php if (!empty($facture['id_devis'])): ?>
                        <a class="pill-link" href="<?= route('devis_show', ['id' => (int) $facture['id_devis']]) ?>">VOIR LE DEVIS</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>