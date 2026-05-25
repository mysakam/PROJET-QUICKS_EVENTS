<h1>Mes devis</h1>

<?php
if (!isset($devisList)) $devisList = [];
?>

<?php if (empty($devisList)): ?>
    <p>Aucun devis trouvé.</p>
<?php else: ?>
    <ul>
        <?php foreach ($devisList as $devis): ?>
            <li>
                <a href="<?= route('devis_show', ['id' => $devis['id']]) ?>">
                    <?= e($devis['reference']) ?>
                </a>
                - <?= e($devis['status']) ?>
                - <?= e($devis['total_amount']) ?> €
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>