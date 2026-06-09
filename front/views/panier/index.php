<?php
if (!isset($cart)) $cart = [];
if (!isset($total)) $total = 0;
?>
<section class="apropos">
    <div class="admin-media-shell">
        <h1 class="titre-texte"><span>M</span>on panier</h1>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('catalogues') ?>">Continuer mes choix</a>
        </div>

        <?php if (empty($cart)): ?>
            <div class="panier-summary-card">
                <p>Votre panier est vide.</p>
            </div>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table panier-table">
                    <thead>
                        <tr>
                            <th>Prestation</th>
                            <th>Categorie</th>
                            <th>Prix unitaire</th>
                            <th>Quantite</th>
                            <th>Sous-total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $id => $item): ?>
                            <tr>
                                <td><?= e($item['name']) ?></td>
                                <td><?= e($item['category']) ?></td>
                                <td><?= number_format((float) $item['price'], 2, ',', ' ') ?> EUR</td>
                                <td><?= (int) $item['quantity'] ?></td>
                                <td><?= number_format((float) $item['price'] * (int) $item['quantity'], 2, ',', ' ') ?> EUR</td>
                                <td>
                                    <form action="<?= route('panier_remove', ['id' => $id]) ?>" method="post">
                                        <button class="admin-btn admin-btn-danger" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="panier-summary-card">
                <p><strong>Total:</strong> <?= number_format((float) $total, 2, ',', ' ') ?> EUR</p>
            </div>

            <div class="admin-form-actions">
                <form action="<?= route('panier_clear') ?>" method="post">
                    <button class="admin-btn admin-btn-danger" type="submit">Vider le panier</button>
                </form>
                <a class="btn" href="<?= route('devis_checkout') ?>">Demander un devis</a>
            </div>
        <?php endif; ?>
    </div>
</section>