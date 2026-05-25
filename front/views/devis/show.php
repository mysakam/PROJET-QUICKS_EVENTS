<h1>Détail du devis</h1>

<?php
if (!isset($devis)) $devis = ['reference' => '', 'status' => '', 'total_amount' => '', 'event_date' => '', 'client_message' => '', 'items' => []];
?>

<p>Référence : <?= e($devis['reference']) ?></p>
<p>Statut : <?= e($devis['status']) ?></p>
<p>Total : <?= e($devis['total_amount']) ?> €</p>
<p>Date événement : <?= e($devis['event_date'] ?? '') ?></p>
<p>Message : <?= e($devis['client_message'] ?? '') ?></p>

<h2>Prestations</h2>

<?php if (empty($devis['items'])): ?>
    <p>Aucune ligne.</p>
<?php else: ?>
    <ul>
        <?php foreach ($devis['items'] as $item): ?>
            <li>
                <?= e($item['name']) ?> -
                <?= e($item['category']) ?> -
                <?= e($item['price']) ?> €
                x <?= e($item['quantity']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>
    <a href="<?= route('devis_index') ?>">Retour à mes devis</a>
</p>