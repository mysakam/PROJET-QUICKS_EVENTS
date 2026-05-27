<h1>Mes devis</h1>

<?php if (empty($devisList)): ?>
<p>Vous n'avez encore aucun devis.</p>
<?php else: ?>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Référence</th>
            <th>Statut</th>
            <th>Date événement</th>
            <th>Montant</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($devisList as $devis): ?>
        <tr>
            <td><?= htmlspecialchars($devis['reference']) ?></td>
            <td><?= htmlspecialchars($devis['statut']) ?></td>
            <td><?= htmlspecialchars($devis['date_evenement'] ?? '') ?></td>
            <td><?= number_format($devis['montant_total'], 2, ',', ' ') ?> €</td>
            <td>
                <a href="<?= route('devis_show', ['id' => $devis['id_devis']]) ?>">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>