<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<h1>Catalogues</h1>

<?php
if (!isset($categories)) $categories = [];
?>

<?php if (empty($categories)): ?>
    <p>Aucune catégorie disponible.</p>
<?php else: ?>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="<?= route('catalogues_category', ['slug' => $category['slug']]) ?>">
                    <?= e($category['nom']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p>
    <a href="<?= route('panier') ?>">Voir mon panier</a>
</p>