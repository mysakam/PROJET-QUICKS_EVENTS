<h1>Détail du devis</h1>

<p><strong>Référence :</strong> <?= htmlspecialchars($devis['reference']) ?></p>
<p><strong>Statut :</strong> <?= htmlspecialchars($devis['statut']) ?></p>
<p><strong>Montant total :</strong> <?= number_format($devis['montant_total'], 2, ',', ' ') ?> €</p>

<?php if (!empty($devis['date_evenement'])): ?>
<p><strong>Date de l'événement :</strong> <?= htmlspecialchars($devis['date_evenement']) ?></p>
<?php endif; ?>

<?php if (!empty($devis['message_client'])): ?>
<p><strong>Message :</strong> <?= nl2br(htmlspecialchars($devis['message_client'])) ?></p>
<?php endif; ?>

<h2>Lignes du devis</h2>

<?php if (empty($lignes)): ?>
<p>Aucune ligne trouvée.</p>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Prestation</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Montant ligne</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lignes as $ligne): ?>
        <tr>
            <td><?= htmlspecialchars($ligne['nom']) ?></td>
            <td><?= (int) $ligne['quantite'] ?></td>
            <td><?= number_format($ligne['prix_unitaire'], 2, ',', ' ') ?> €</td>
            <td><?= number_format($ligne['montant_ligne'], 2, ',', ' ') ?> €</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<p>
    <a href="<?= route('devis_index') ?>">Retour à mes devis</a>
</p>