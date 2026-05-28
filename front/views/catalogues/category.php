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
                <strong><?= e($prestation['nom']) ?></strong>
                - <?= e($prestation['category_name']) ?>
                - <?= e($prestation['prix_unitaire']) ?> €
                <a href="<?= route('prestations_show', ['id' => $prestation['id_prestation']]) ?>">
                    Voir
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>
    <a href="<?= route('catalogues') ?>">Retour catalogues</a>
</p>