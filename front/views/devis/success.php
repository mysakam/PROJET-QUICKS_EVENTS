<h1>Demande envoyée</h1>

<p>Votre devis a bien été créé.</p>
<p>Référence : <?= e($devis['reference']) ?></p>
<p>Statut : <?= e($devis['status']) ?></p>
<p>Total : <?= e($devis['total_amount']) ?> €</p>
<p>Date de création : <?= e($devis['created_at']) ?></p>

<p>
    <a href="<?= route('devis_show', ['id' => $devis['id']]) ?>">Voir le devis</a>
</p>

<p>
    <a href="<?= route('catalogues') ?>">Retour au catalogue</a>
</p>