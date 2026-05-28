<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<h1><?= e($prestation['nom']) ?></h1>

<?php
if (!isset($prestation)) $prestation = ['name' => '', 'category' => '', 'price' => '', 'description' => '', 'id' => ''];
?>

<p>Catégorie : <?= e($prestation['category_name']) ?></p>
<p>Prix : <?= e($prestation['prix_unitaire']) ?> €</p>
<p>Description : <?= e($prestation['description']) ?></p>

<form action="<?= route('panier_add', ['id' => $prestation['id_prestation']]) ?>" method="post">
    <button type="submit">Ajouter au panier</button>
</form>

<p>
    <a href="<?= route('catalogues_category', ['slug' => $prestation['category_slug']]) ?>">
        Retour catégorie
    </a>
</p>