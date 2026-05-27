<h1>Catégorie : <?= e($category['nom']) ?></h1>

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