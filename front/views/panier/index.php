<h1>Mon panier</h1>

<?php
if (!isset($cart)) $cart = [];
if (!isset($total)) $total = 0;
?>

<?php if (empty($cart)): ?>
<p>Votre panier est vide.</p>
<?php else: ?>
<ul>
    <?php foreach ($cart as $id => $item): ?>
    <li>
        <strong><?= e($item['name']) ?></strong>
        - <?= e($item['category']) ?>
        - <?= number_format((float) $item['price'], 2, ',', ' ') ?> €
        - Quantité : <?= (int) $item['quantity'] ?>

        <form action="<?= route('panier_remove', ['id' => $id]) ?>" method="post" style="display:inline;">
            <button type="submit">Supprimer</button>
        </form>
    </li>
    <?php endforeach; ?>
</ul>

<p><strong>Total :</strong> <?= number_format((float) $total, 2, ',', ' ') ?> €</p>

<form action="<?= route('panier_clear') ?>" method="post">
    <button type="submit">Vider le panier</button>
</form>

<p>
    <a href="<?= route('devis_checkout') ?>">Demander un devis</a>
</p>
<?php endif; ?>

<p>
    <a href="<?= route('catalogues') ?>">Continuer mes choix</a>
</p>