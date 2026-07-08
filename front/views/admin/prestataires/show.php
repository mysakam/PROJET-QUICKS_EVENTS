<?php
$prestataire = $prestataire ?? [];
$prestations = $prestations ?? [];
$activitySummary = $activitySummary ?? ['total_devis' => 0, 'total_factures' => 0, 'montant_factures' => 0.0];
$devisByStatus = $devisByStatus ?? [];
$facturesByStatus = $facturesByStatus ?? [];
$recentDevis = $recentDevis ?? [];
$recentFactures = $recentFactures ?? [];
$selectedMonth = (int) ($selectedMonth ?? date('n'));
$selectedYear = (int) ($selectedYear ?? date('Y'));
$disponibilites = $disponibilites ?? [];
$providerMedias = $providerMedias ?? [];
$providerMediaThemeSlug = $providerMediaThemeSlug ?? ('prestataire-' . (int) ($prestataire['id_prestataire'] ?? 0));
$lang = $lang ?? current_lang();

$statusLabel = static function (?string $status) use ($lang): string {
    $normalized = strtolower(trim((string) $status));
    if ($normalized === '') {
        return '-';
    }

    $key = 'admin.status.' . $normalized;
    $translated = t($key, $lang);
    if ($translated !== $key) {
        return $translated;
    }

    $label = str_replace('_', ' ', $normalized);
    return ucfirst($label);
};
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><?= e(t('admin.prestataires.show_title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="admin-alert admin-alert-success\"><?= e($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error\"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_prestataires_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.back_list', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_prestataires_edit', ['id' => $prestataire['id_prestataire']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.edit', $lang)) ?></a>
        </div>

        <div class="admin-kpi-grid">
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_quotes', $lang)) ?></h3>
                <p><?= (int) $activitySummary['total_devis'] ?></p>
            </article>
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_invoices', $lang)) ?></h3>
                <p><?= (int) $activitySummary['total_factures'] ?></p>
            </article>
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_invoice_amount', $lang)) ?></h3>
                <p><?= e(number_format((float) $activitySummary['montant_factures'], 2, ',', ' ')) ?> EUR</p>
            </article>
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_rating', $lang)) ?></h3>
                <p><?= e($prestataire['note_sur_10'] !== null && $prestataire['note_sur_10'] !== '' ? $prestataire['note_sur_10'] : '-') ?></p>
            </article>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <tbody>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_name', $lang)) ?></th>
                        <td><?= e($prestataire['nom'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= e($prestataire['email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_phone', $lang)) ?></th>
                        <td><?= e($prestataire['telephone'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_address', $lang)) ?></th>
                        <td><?= e($prestataire['adresse'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_event_type', $lang)) ?></th>
                        <td><?= e($prestataire['type_evenement'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>IBAN</th>
                        <td><?= e($prestataire['iban'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>BIC / SWIFT</th>
                        <td><?= e($prestataire['bic'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_bank_name', $lang)) ?></th>
                        <td><?= e($prestataire['banque_nom'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_holder', $lang)) ?></th>
                        <td><?= e($prestataire['titulaire_compte'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_description', $lang)) ?></th>
                        <td><?= nl2br(e($prestataire['description'] ?? '-')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3>Calendrier de disponibilite</h3>
        <div class="admin-table-wrap">
            <form method="post" action="<?= route('admin_prestataires_disponibilites_save', ['id' => (int) $prestataire['id_prestataire']]) ?>?lang=<?= e($lang) ?>">
                <input type="hidden" name="_csrf_token" value="<?= e(Csrf::token()) ?>">
                <table class="admin-table">
                    <tbody>
                        <tr>
                            <th>Date</th>
                            <td><input type="date" name="date_evenement" required></td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                <select name="statut" required>
                                    <option value="disponible">Disponible</option>
                                    <option value="indisponible">Indisponible</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Commentaire</th>
                            <td><input type="text" name="commentaire" maxlength="255" placeholder="ex: conges, deja reserve"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="admin-media-actions" style="margin-top:12px;">
                    <button class="btn" type="submit">Enregistrer la date</button>
                </div>
            </form>
        </div>

        <h3>Disponibilites du mois <?= e((string) $selectedMonth) ?>/<?= e((string) $selectedYear) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Commentaire</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($disponibilites === []): ?>
                        <tr>
                            <td colspan="4">Aucune disponibilite renseignee pour ce mois.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($disponibilites as $item): ?>
                            <tr>
                                <td><?= e($item['date_evenement'] ?? '-') ?></td>
                                <td><?= e(($item['statut'] ?? '') === 'indisponible' ? 'Indisponible' : 'Disponible') ?></td>
                                <td><?= e($item['commentaire'] ?? '-') ?></td>
                                <td>
                                    <form method="post" action="<?= route('admin_prestataires_disponibilites_save', ['id' => (int) $prestataire['id_prestataire']]) ?>?lang=<?= e($lang) ?>" onsubmit="return confirm('Supprimer cette date du calendrier ?');">
                                        <input type="hidden" name="_csrf_token" value="<?= e(Csrf::token()) ?>">
                                        <input type="hidden" name="action_disponibilite" value="delete">
                                        <input type="hidden" name="date_evenement" value="<?= e($item['date_evenement']) ?>">
                                        <button class="btn" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3>Medias du prestataire</h3>
        <div class="admin-table-wrap">
            <form method="post" action="<?= route('admin_prestataires_medias_save', ['id' => (int) $prestataire['id_prestataire']]) ?>?lang=<?= e($lang) ?>">
                <input type="hidden" name="_csrf_token" value="<?= e(Csrf::token()) ?>">
                <table class="admin-table">
                    <tbody>
                        <tr>
                            <th>Theme</th>
                            <td><input type="text" value="<?= e($providerMediaThemeSlug) ?>" readonly></td>
                        </tr>
                        <tr>
                            <th>Type media</th>
                            <td>
                                <select name="media_type" required>
                                    <option value="image">Image</option>
                                    <option value="video">Video</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>URL media</th>
                            <td><input type="text" name="media_url" required placeholder="/assets/... ou https://..."></td>
                        </tr>
                        <tr>
                            <th>Titre FR</th>
                            <td><input type="text" name="title_fr" required></td>
                        </tr>
                        <tr>
                            <th>Titre EN</th>
                            <td><input type="text" name="title_en" required></td>
                        </tr>
                        <tr>
                            <th>Description FR</th>
                            <td><input type="text" name="description_fr" maxlength="255"></td>
                        </tr>
                        <tr>
                            <th>Description EN</th>
                            <td><input type="text" name="description_en" maxlength="255"></td>
                        </tr>
                        <tr>
                            <th>Position</th>
                            <td><input type="number" name="position" min="1" value="1"></td>
                        </tr>
                        <tr>
                            <th>Actif</th>
                            <td><label><input type="checkbox" name="is_active" checked> Oui</label></td>
                        </tr>
                    </tbody>
                </table>
                <div class="admin-media-actions" style="margin-top:12px;">
                    <button class="btn" type="submit">Ajouter le media</button>
                </div>
            </form>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Apercu</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($providerMedias === []): ?>
                        <tr>
                            <td colspan="7">Aucun media associe a ce prestataire.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($providerMedias as $media): ?>
                            <tr>
                                <td><?= (int) ($media['id_media'] ?? 0) ?></td>
                                <td><?= e($media['media_type'] ?? '-') ?></td>
                                <td>
                                    <?php if (($media['media_type'] ?? 'image') === 'video'): ?>
                                        <a class="btn" href="<?= e(img_url((string) ($media['media_url'] ?? ''))) ?>" target="_blank" rel="noopener noreferrer">Voir video</a>
                                    <?php else: ?>
                                        <a class="btn" href="<?= e(img_url((string) ($media['media_url'] ?? ''))) ?>" target="_blank" rel="noopener noreferrer">Voir image</a>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($media['title'] ?? '-') ?></td>
                                <td><?= e($media['description'] ?? '-') ?></td>
                                <td><?= (int) ($media['position'] ?? 0) ?></td>
                                <td>
                                    <form method="post" action="<?= route('admin_prestataires_medias_delete', ['id' => (int) $prestataire['id_prestataire'], 'mediaId' => (int) ($media['id_media'] ?? 0)]) ?>?lang=<?= e($lang) ?>" onsubmit="return confirm('Supprimer ce media ?');">
                                        <input type="hidden" name="_csrf_token" value="<?= e(Csrf::token()) ?>">
                                        <button class="btn" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.associated_services', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_service', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.category', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_unit_price', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_description', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestations)): ?>
                        <tr>
                            <td colspan="4"><?= e(t('admin.prestataires.none_services', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prestations as $prestation): ?>
                            <tr>
                                <td><?= e($prestation['nom'] ?? '-') ?></td>
                                <td><?= e($prestation['category_name'] ?? '-') ?></td>
                                <td><?= e(number_format((float) ($prestation['prix_unitaire'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                                <td><?= nl2br(e($prestation['description'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.quote_statuses', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_total', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devisByStatus)): ?>
                        <tr>
                            <td colspan="2"><?= e(t('admin.prestataires.none_quotes', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($devisByStatus as $row): ?>
                            <tr>
                                <td><?= e($statusLabel((string) ($row['statut'] ?? ''))) ?></td>
                                <td><?= (int) $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.invoice_statuses', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_total', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($facturesByStatus)): ?>
                        <tr>
                            <td colspan="2"><?= e(t('admin.prestataires.none_invoices', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($facturesByStatus as $row): ?>
                            <tr>
                                <td><?= e($statusLabel((string) ($row['statut'] ?? ''))) ?></td>
                                <td><?= (int) $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.recent_quotes', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_ref', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_provider_amount', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_date', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentDevis)): ?>
                        <tr>
                            <td colspan="4"><?= e(t('admin.prestataires.none_recent_quotes', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentDevis as $devis): ?>
                            <tr>
                                <td><?= e($devis['reference']) ?></td>
                                <td><?= e($statusLabel((string) ($devis['statut'] ?? ''))) ?></td>
                                <td><?= e(number_format((float) $devis['montant_prestataire'], 2, ',', ' ')) ?> EUR</td>
                                <td><?= e($devis['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.recent_invoices', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_ref', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_provider_amount', $lang)) ?></th>
                        <th><?= e(t('admin.factures.label_issue', $lang)) ?></th>
                        <th><?= e(t('admin.factures.label_due', $lang)) ?></th>
                        <th><?= e(t('admin.factures.label_payment', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentFactures)): ?>
                        <tr>
                            <td colspan="6"><?= e(t('admin.prestataires.none_recent_invoices', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentFactures as $facture): ?>
                            <tr>
                                <td><?= e($facture['reference']) ?></td>
                                <td><?= e($statusLabel((string) ($facture['statut'] ?? ''))) ?></td>
                                <td><?= e(number_format((float) $facture['montant_prestataire'], 2, ',', ' ')) ?> EUR</td>
                                <td><?= e($facture['date_emission'] ?? '-') ?></td>
                                <td><?= e($facture['date_echeance'] ?? '-') ?></td>
                                <td><?= e($facture['date_paiement'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>