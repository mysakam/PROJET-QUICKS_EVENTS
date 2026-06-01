<?php if (empty($cart)): ?>
    <div class="empty-box">
        <p>Votre panier est vide.</p>
    </div>
<?php else: ?>
    <div class="checkout-box">
        <div class="checkout-grid">
            <section class="checkout-card">
                <h2>Récapitulatif</h2>

                <div class="cart-list">
                    <?php foreach ($cart as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-title"><?= e($item['name'] ?? 'Prestation') ?></div>
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

                <form method="POST" action="<?= route('devis_store') ?>">
                    <div class="form-group">
                        <label for="date_evenement">Date de l'événement</label>
                        <input class="form-control" type="date" name="date_evenement" id="date_evenement">
                    </div>

                    <div class="form-group">
                        <label for="message_client">Message</label>
                        <textarea class="form-control" name="message_client" id="message_client"></textarea>
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