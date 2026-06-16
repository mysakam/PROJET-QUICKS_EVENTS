<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$oldDevisForm = $oldDevisForm ?? [];
$oldDateEvenement = (string) ($oldDevisForm['date_evenement'] ?? '');
$oldMessageClient = (string) ($oldDevisForm['message_client'] ?? '');

$txt = [
    'fr' => [
        'empty' => 'Votre panier est vide.',
        'summary' => 'Récapitulatif',
        'event' => 'Fiche événement enregistrée',
        'type' => 'Type',
        'guests' => 'Nombre d\'invités',
        'budget' => 'Budget estimatif',
        'selected' => 'Package sélectionné',
        'finalize' => 'Finaliser la proposition de devis',
        'date' => "Date de l'événement",
        'message' => 'Message',
        'modify' => 'MODIFIER',
        'save' => 'ENREGISTRER',
    ],
    'en' => [
        'empty' => 'Your cart is empty.',
        'summary' => 'Summary',
        'event' => 'Saved event form',
        'type' => 'Type',
        'guests' => 'Number of guests',
        'budget' => 'Estimated budget',
        'selected' => 'Selected package',
        'finalize' => 'Finalize the quote proposal',
        'date' => 'Event date',
        'message' => 'Message',
        'modify' => 'EDIT',
        'save' => 'SAVE',
    ],
];
$t = $txt[$lang];
?>

<?php if (empty($cart)): ?>
    <div class="empty-box">
        <p><?= e($t['empty']) ?></p>
    </div>
<?php else: ?>
    <div class="checkout-box">
        <div class="checkout-grid">
            <section class="checkout-card">
                <h2><?= e($t['summary']) ?></h2>

                <?php if (!empty($eventRequest)): ?>
                    <div class="checkout-event-summary">
                        <p><strong><?= e($t['event']) ?></strong></p>
                        <ul>
                            <?php if (!empty($eventRequest['type_evenement'])): ?><li><?= e($t['type']) ?> : <?= e($eventRequest['type_evenement']) ?></li><?php endif; ?>
                            <?php if (!empty($eventRequest['nb_personnes'])): ?><li><?= e($t['guests']) ?> : <?= e($eventRequest['nb_personnes']) ?></li><?php endif; ?>
                            <?php if (!empty($eventRequest['budget'])): ?><li><?= e($t['budget']) ?> : <?= e($eventRequest['budget']) ?></li><?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="cart-list">
                    <?php foreach ($cart as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-title\"><?= e($item['name'] ?? 'Prestation') ?></div>
                            <?php if (!empty($item['is_package'])): ?>
                                <span class="checkout-package-badge"><?= e($t['selected']) ?></span>
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
                <h2><?= e($t['finalize']) ?></h2>

                <form method="POST" action="<?= route('devis_store') . '?lang=' . $lang ?>" data-fetch-form>
                    <div class="form-group">
                        <label for="date_evenement"><?= e($t['date']) ?></label>
                        <input class="form-control" type="date" name="date_evenement" id="date_evenement" required value="<?= e($oldDateEvenement) ?>">
                    </div>

                    <div class="form-group">
                        <label for="message_client"><?= e($t['message']) ?></label>
                        <textarea class="form-control" name="message_client" id="message_client"><?= e($oldMessageClient) ?></textarea>
                    </div>

                    <div class="total-box">
                        TOTAL : <?= number_format((float)$total, 2, ',', ' ') ?> €
                    </div>

                    <div class="checkout-actions">
                        <a class="pill-link" href="<?= route('panier') . '?lang=' . $lang ?>"><?= e($t['modify']) ?></a>
                        <button class="pill-btn" type="submit"><?= e($t['save']) ?></button>
                    </div>
                </form>
            </section>
        </div>
    </div>
<?php endif; ?>