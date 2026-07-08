<?php

class AdminDevisController extends AdminBaseController
{
    private DevisModel $devisModel;
    private DevisLigneModel $devisLigneModel;
    private FactureModel $factureModel;
    private PrestataireDisponibiliteModel $disponibiliteModel;

    public function __construct()
    {
        $this->devisModel = new DevisModel();
        $this->devisLigneModel = new DevisLigneModel();
        $this->factureModel = new FactureModel();
        $this->disponibiliteModel = new PrestataireDisponibiliteModel();
    }

    private function buildDisponibiliteChecks(array $lignes, ?string $dateEvenement): array
    {
        if ($dateEvenement === null || trim($dateEvenement) === '') {
            return [];
        }

        $prestataireByPrestation = [];
        $prestationIds = [];
        foreach ($lignes as $ligne) {
            $idPrestation = (int) ($ligne['id_prestation'] ?? 0);
            if ($idPrestation > 0) {
                $prestationIds[] = $idPrestation;
            }
        }

        $prestationIds = array_values(array_unique($prestationIds));
        if ($prestationIds === []) {
            return [];
        }

        $pdo = Database::getPdo();
        $placeholders = implode(',', array_fill(0, count($prestationIds), '?'));
        $stmt = $pdo->prepare(
            "SELECT p.id_prestation, p.id_prestataire, pr.nom AS prestataire_nom
             FROM prestations p
             INNER JOIN prestataires pr ON pr.id_prestataire = p.id_prestataire
             WHERE p.id_prestation IN ($placeholders)"
        );
        $stmt->execute($prestationIds);

        $prestataireIds = [];
        foreach ($stmt->fetchAll() as $row) {
            $idPrestation = (int) $row['id_prestation'];
            $idPrestataire = (int) $row['id_prestataire'];
            $prestataireByPrestation[$idPrestation] = [
                'id_prestataire' => $idPrestataire,
                'prestataire_nom' => $row['prestataire_nom'] ?? ('Prestataire #' . $idPrestataire),
            ];
            $prestataireIds[] = $idPrestataire;
        }

        $prestataireIds = array_values(array_unique($prestataireIds));
        $statuses = $this->disponibiliteModel->findStatusesForPrestatairesByDate($prestataireIds, $dateEvenement);

        $checks = [];
        foreach ($lignes as $ligne) {
            $idPrestation = (int) ($ligne['id_prestation'] ?? 0);
            if (!isset($prestataireByPrestation[$idPrestation])) {
                continue;
            }

            $provider = $prestataireByPrestation[$idPrestation];
            $statusRow = $statuses[(int) $provider['id_prestataire']] ?? null;
            $status = $statusRow['statut'] ?? 'non_renseigne';

            $checks[] = [
                'prestation_nom' => $ligne['nom'] ?? ('Prestation #' . $idPrestation),
                'prestataire_nom' => $provider['prestataire_nom'],
                'statut' => $status,
                'commentaire' => $statusRow['commentaire'] ?? null,
            ];
        }

        return $checks;
    }

    public function show(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $devis = $this->devisModel->findById($id);
        if (!$devis) {
            http_response_code(404);
            echo 'Devis introuvable.';
            return;
        }

        $client = null;
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare(
            'SELECT id_client, nom, prenom, email
             FROM clients
             WHERE id_client = :id_client
             LIMIT 1'
        );
        $stmt->execute(['id_client' => (int) $devis['id_client']]);
        $client = $stmt->fetch() ?: null;

        $this->render('admin/devis/show', [
            'devis' => $devis,
            'client' => $client,
            'lignes' => $lignes = $this->devisLigneModel->findByDevisId($id),
            'facture' => $this->factureModel->findByDevisId($id),
            'disponibiliteChecks' => $this->buildDisponibiliteChecks($lignes, (string) ($devis['date_evenement'] ?? '')),
            'pageTitle' => 'Fiche devis',
            'lang' => $this->getLang(),
        ]);
    }
}
