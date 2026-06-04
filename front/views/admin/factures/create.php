<?php
$devisOptions = $devisOptions ?? [];
$defaultReference = $defaultReference ?? '';
$statuses = $statuses ?? ['emise', 'envoyee', 'payee', 'annulee', 'en_retard'];
?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>A</span>jouter une facture</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($devisOptions)): ?>
            <p>Tous les devis ont deja une facture.</p>
            <a class="btn" href="<?= route('admin_factures_index') ?>">Retour</a>
        <?php else: ?>
            <form class="admin-form" method="POST" action="<?= route('admin_factures_store') ?>">
                <div class="admin-form-row">
                    <label for="id_devis">Devis</label>
                    <select id="id_devis" name="id_devis" required>
                        <?php foreach ($devisOptions as $d): ?>
                            <option value="<?= (int) $d['id_devis'] ?>">
                                <?= e($d['reference']) ?> - <?= e(trim(($d['prenom'] ?? '') . ' ' . ($d['nom'] ?? ''))) ?> (<?= e(number_format((float) $d['montant_total'], 2, ',', ' ')) ?> EUR)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-row">
                    <label for="reference">Reference facture</label>
                    <input id="reference" name="reference" type="text" value="<?= e($defaultReference) ?>" required>
                </div>
                <div class="admin-form-row">
                    <label for="statut">Statut</label>
                    <select id="statut" name="statut" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= e($status) ?>"><?= e($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-row">
                    <label for="montant_ttc">Montant TTC</label>
                    <input id="montant_ttc" name="montant_ttc" type="text" value="0" required>
                </div>
                <div class="admin-form-row">
                    <label for="date_emission">Date emission</label>
                    <input id="date_emission" name="date_emission" type="date">
                </div>
                <div class="admin-form-row">
                    <label for="date_echeance">Date echeance</label>
                    <input id="date_echeance" name="date_echeance" type="date">
                </div>
                <div class="admin-form-row">
                    <label for="date_paiement">Date paiement</label>
                    <input id="date_paiement" name="date_paiement" type="date">
                </div>
                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit">Enregistrer</button>
                    <a class="btn" href="<?= route('admin_factures_index') ?>">Retour</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>