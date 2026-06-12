<?php
$oldDevisForm = $oldDevisForm ?? [];
$oldDateEvenement = (string) ($oldDevisForm['date_evenement'] ?? '');
$oldMessageClient = (string) ($oldDevisForm['message_client'] ?? '');
?>

<?php if (empty($cart)): ?>
    <div class="empty-box">
        <p>Votre panier est vide.</p>
    </div>
<?php else: ?>
    <div class="checkout-box">
        <div class="checkout-grid">
            <section class="checkout-card">
                <h2>Récapitulatif</h2>

                <?php if (!empty($eventRequest)): ?>
                    <div class="checkout-event-summary">
                        <p><strong>Fiche événement enregistrée</strong></p>
                        <ul>
                            <?php if (!empty($eventRequest['type_evenement'])): ?><li>Type : <?= e($eventRequest['type_evenement']) ?></li><?php endif; ?>
                            <?php if (!empty($eventRequest['nb_personnes'])): ?><li>Nombre d'invités : <?= e($eventRequest['nb_personnes']) ?></li><?php endif; ?>
                            <?php if (!empty($eventRequest['budget'])): ?><li>Budget estimatif : <?= e($eventRequest['budget']) ?></li><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="cart-list">
                    <?php foreach ($cart as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-title\"><?= e($item['name'] ?? 'Prestation') ?></div>
                            <?php if (!empty($item['is_package'])): ?>
                                <span class="checkout-package-badge">Package sélectionné</span>
                            <?php endif; ?>
                            <div class="cart-item-meta">
                                Quantité : <?= (int)($item['quantity'] ?? 0) ?><br>
                                Prix unitaire : <?= number_format((float)($item['price'] ?? 0), 2, ',', ' ') ?> €<br>
                                Montant :
                                <?= number_format((float)(($item['price'] ?? 0) * ($item['quantity'] ?? 0)), 2, ',', ' ') ?> €
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="checkout-card">
                <h2>Finaliser le devis</h2>

                <form method="POST" action="<?= route('devis_store') ?>" data-fetch-form>
                    <div class="form-group">
                        <label for="date_evenement">Date de l'événement</label>
                        <input class="form-control" type="date" name="date_evenement" id="date_evenement" required value="<?= e($oldDateEvenement) ?>">
                    </div>

                    <div class="form-group">
                        <label for="message_client">Message</label>
                        <textarea class="form-control" name="message_client" id="message_client"><?= e($oldMessageClient) ?></textarea>
                    </div>

                    <div class="total-box">
                        TOTAL : <?= number_format((float)$total, 2, ',', ' ') ?> €
                    </div>

                    <div class="checkout-actions">
                        <a class="pill-link" href="<?= route('panier') ?>">MODIFIER</a>
                        <button class="pill-btn" type="submit">ENREGISTRER</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
<?php endif; ?>