<?php
$categories = $categories ?? [];
$prestationsFormData = $prestationsFormData ?? [[
    'id_prestation' => 0,
    'id_categorie' => 0,
    'nom' => '',
    'description' => '',
    'prix_unitaire' => '',
]];
?>
<div class="admin-form-row">
    <label>Prestations associées</label>
    <div data-prestations-list>
        <?php foreach ($prestationsFormData as $index => $prestation): ?>
            <div class="admin-table-wrap" data-prestation-item style="padding: 14px; margin-bottom: 12px;">
                <input type="hidden" name="prestation_id[]" value="<?= (int) ($prestation['id_prestation'] ?? 0) ?>">
                <div class="admin-form-row">
                    <label>Catégorie de prestation</label>
                    <select name="prestation_category_id[]">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id_categorie'] ?>" <?= ((int) ($prestation['id_categorie'] ?? 0) === (int) $category['id_categorie']) ? 'selected' : '' ?>>
                                <?= e($category['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-row">
                    <label>Nom de la prestation</label>
                    <input name="prestation_nom[]" type="text" value="<?= e($prestation['nom'] ?? '') ?>" placeholder="Ex: Buffet premium">
                </div>
                <div class="admin-form-row">
                    <label>Prix unitaire prestation</label>
                    <input name="prestation_prix[]" type="number" min="0" step="0.01" value="<?= e((string) ($prestation['prix_unitaire'] ?? '')) ?>" placeholder="Ex: 250">
                </div>
                <div class="admin-form-row">
                    <label>Description prestation</label>
                    <textarea name="prestation_description[]" rows="3" placeholder="Détails de la prestation..."><?= e($prestation['description'] ?? '') ?></textarea>
                </div>
                <button class="admin-btn admin-btn-danger" type="button" data-remove-prestation>Retirer cette prestation</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="admin-btn" type="button" data-add-prestation>Ajouter une prestation</button>
</div>

<template data-prestation-template>
    <div class="admin-table-wrap" data-prestation-item style="padding: 14px; margin-bottom: 12px;">
        <input type="hidden" name="prestation_id[]" value="0">
        <div class="admin-form-row">
            <label>Catégorie de prestation</label>
            <select name="prestation_category_id[]">
                <option value="">-- Sélectionner --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id_categorie'] ?>"><?= e($category['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-form-row">
            <label>Nom de la prestation</label>
            <input name="prestation_nom[]" type="text" value="" placeholder="Ex: Buffet premium">
        </div>
        <div class="admin-form-row">
            <label>Prix unitaire prestation</label>
            <input name="prestation_prix[]" type="number" min="0" step="0.01" value="" placeholder="Ex: 250">
        </div>
        <div class="admin-form-row">
            <label>Description prestation</label>
            <textarea name="prestation_description[]" rows="3" placeholder="Détails de la prestation..."></textarea>
        </div>
        <button class="admin-btn admin-btn-danger" type="button" data-remove-prestation>Retirer cette prestation</button>
    </div>
</template>

<script>
    (function() {
        var list = document.querySelector('[data-prestations-list]');
        var template = document.querySelector('[data-prestation-template]');
        var addButton = document.querySelector('[data-add-prestation]');

        if (!list || !template || !addButton) {
            return;
        }

        function bindRemoveButtons(scope) {
            var buttons = (scope || document).querySelectorAll('[data-remove-prestation]');
            buttons.forEach(function(button) {
                if (button.dataset.bound === '1') {
                    return;
                }
                button.dataset.bound = '1';
                button.addEventListener('click', function() {
                    var items = list.querySelectorAll('[data-prestation-item]');
                    if (items.length === 1) {
                        items[0].querySelectorAll('input[type="text"], input[type="number"], textarea').forEach(function(field) {
                            field.value = '';
                        });
                        items[0].querySelectorAll('select').forEach(function(field) {
                            field.selectedIndex = 0;
                        });
                        var hidden = items[0].querySelector('input[name="prestation_id[]"]');
                        if (hidden) {
                            hidden.value = '0';
                        }
                        return;
                    }

                    var item = button.closest('[data-prestation-item]');
                    if (item) {
                        item.remove();
                    }
                });
            });
        }

        addButton.addEventListener('click', function() {
            list.appendChild(template.content.cloneNode(true));
            bindRemoveButtons(list);
        });

        bindRemoveButtons(list);
    })();
</script>