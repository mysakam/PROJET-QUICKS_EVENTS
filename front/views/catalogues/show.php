<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<h1><?= e($prestation['name']) ?></h1>

<?php
if (!isset($prestation)) $prestation = ['name' => '', 'category' => '', 'price' => '', 'description' => '', 'id' => ''];
?>

<p>Catégorie : <?= e($prestation['category']) ?></p>
<p>Prix : <?= e($prestation['price']) ?> €</p>
<p>Description : <?= e($prestation['description']) ?></p>

<form action="<?= route('panier_add', ['id' => $prestation['id']]) ?>" method="post">
    <button type="submit">Ajouter au panier</button>
</form>

<p>
    <a href="<?= route('catalogues_category', ['slug' => strtolower($prestation['category'])]) ?>">
        Retour catégorie
    </a>
</p>