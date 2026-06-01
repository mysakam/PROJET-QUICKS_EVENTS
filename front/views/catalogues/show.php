<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
if (!isset($prestation)) {
    $prestation = [
        'nom' => '',
        'category_name' => '',
        'prix_unitaire' => '',
        'description' => '',
        'id_prestation' => '',
        'category_slug' => ''
    ];
}
?>

<h1><?= e($prestation['nom']) ?></h1>

<p>Catégorie : <?= e($prestation['category_name']) ?></p>
<p>Prix : <?= e($prestation['prix_unitaire']) ?> €</p>
<p>Description : <?= e($prestation['description']) ?></p>

<?php if (!empty($_SESSION['client'])): ?>
    <form action="<?= route('panier_add', ['id' => $prestation['id_prestation']]) ?>" method="post">
        <button type="submit">Ajouter au panier</button>
    </form>
<?php else: ?>
    <p>
        Connectez-vous pour demander un devis.
        <a href="<?= route('login') ?>">Connexion</a>
    </p>
<?php endif; ?>

<p>
    <a href="<?= route('catalogues_category', ['slug' => $prestation['category_slug']]) ?>">
        Retour catégorie
    </a>
</p>