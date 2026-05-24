<h1>Catalogues</h1>

<?php if (empty($categories)): ?>
<p>Aucune catégorie disponible.</p>
<?php else: ?>
<ul>
    <?php foreach ($categories as $category): ?>
    <li>
        <a href="<?= route('catalogues_category', ['slug' => $category['slug']]) ?>">
            <?= e($category['name']) ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<p>
    <a href="<?= route('panier') ?>">Voir mon panier</a>
</p>