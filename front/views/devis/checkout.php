<h1>Demande de devis</h1>

<?php if (empty($cart)): ?>
<p>Votre panier est vide.</p>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Prestation</th>
            <th>Catégorie</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Total ligne</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cart as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['category']) ?></td>
            <td><?= number_format($item['price'], 2, ',', ' ') ?> €</td>
            <td><?= (int) $item['quantity'] ?></td>
            <td><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> €</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><strong>Total :</strong> <?= number_format($total, 2, ',', ' ') ?> €</p>

<form action="<?= route('devis_store') ?>" method="post">
    <div>
        <label for="date_evenement">Date de l'événement</label>
        <input type="date" name="date_evenement" id="date_evenement">
    </div>

    <div>
        <label for="message_client">Message</label>
        <textarea name="message_client" id="message_client" rows="5"></textarea>
    </div>

    <button type="submit">Valider la demande de devis</button>
</form>
<?php endif; ?>