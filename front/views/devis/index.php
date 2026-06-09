<?php if (empty($devisList)): ?>
    <div class="empty-box">
        <p>Aucun devis trouvé.</p>
    </div>
<?php else: ?>
    <section class="devis-index-list">
        <?php foreach ($devisList as $devis): ?>
            <article class="devis-card">
                <div class="devis-card-top">
                    <div>
                        <span class="devis-card-label">Référence</span>
                        <div class="devis-card-value"><?= e($devis['reference'] ?? '') ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label">Statut</span>
                        <div class="devis-card-value"><?= e($devis['statut'] ?? '') ?></div>
                    </div>

                    <div>
                        <span class="devis-card-label">Date de création du devis</span>
                        <div class="devis-card-value">
                            <?= !empty($devis['created_at']) ? date('d/m/Y', strtotime($devis['created_at'])) : '-' ?>
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label">Date de réservation de l'événement</span>
                        <div class="devis-card-value">
                            <?= !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : '-' ?>
                        </div>
                    </div>

                    <div>
                        <span class="devis-card-label">Total</span>
                        <div class="devis-card-value">
                            <?= number_format((float)($devis['montant_total'] ?? 0), 2, ',', ' ') ?> €
                        </div>
                    </div>
                </div>

                <div class="devis-card-actions">
                    <a class="pill-link" href="<?= route('devis_show', ['id' => $devis['id_devis']]) ?>">VOIR LE DEVIS</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>