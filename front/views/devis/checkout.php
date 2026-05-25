<h1>Demande de devis</h1>

<?php
if (!isset($cart)) $cart = [];
if (!isset($total)) $total = 0;
?>

<?php if (empty($cart)): ?>
    <p>Votre panier est vide.</p>
<?php else: ?>
    <ul>
        <?php foreach ($cart as $item): ?>
            <li>
                <?= e($item['name']) ?> -
                <?= e($item['category']) ?> -
                <?= e($item['price']) ?> €
                x <?= e($item['quantity']) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <p><strong>Total :</strong> <?= e($total) ?> €</p>

    <form action="<?= route('devis_store') ?>" method="post">
        <p>
            <label for="event_date">Date de l'événement :</label><br>
            <input type="date" name="event_date" id="event_date">
        </p>

        <p>
            <label for="client_message">Message :</label><br>
            <textarea name="client_message" id="client_message" rows="5"></textarea>
        </p>

        <button type="submit">Valider la demande de devis</button>
    </form>
<?php endif; ?>

<p>
    <a href="<?= route('panier') ?>">Retour panier</a>
</p>