<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<h1>Catégorie : <?= e($slug) ?></h1>

<?php
if (!isset($slug)) $slug = '';
if (!isset($prestations)) $prestations = [];
?>

<?php if (empty($prestations)): ?>
    <p>Aucune prestation trouvée.</p>
<?php else: ?>
    <ul>
        <?php foreach ($prestations as $prestation): ?>
            <li>
                <strong><?= e($prestation['name']) ?></strong>
                - <?= e($prestation['category']) ?>
                - <?= e($prestation['price']) ?> €
                <a href="<?= route('prestations_show', ['id' => $prestation['id']]) ?>">
                    Voir
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>
    <a href="<?= route('catalogues') ?>">Retour catalogues</a>
</p>