<?php

class DevisController extends Controller
{
    private DevisModel $devisModel;
    private DevisLigneModel $devisLigneModel;
    private FactureModel $factureModel;
    private PDO $pdo;

    public function __construct()
    {
        $this->devisModel = new DevisModel();
        $this->devisLigneModel = new DevisLigneModel();
        $this->factureModel = new FactureModel();
        $this->pdo = Database::getPdo();
    }

    private function currentClientId(): int
    {
        return (int) ($_SESSION['client']['id_client'] ?? 1);
    }

    private function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    private function clearCart(): void
    {
        unset($_SESSION['cart']);
    }

    public function checkout(): void
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            redirect(route('panier'));
            return;
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        $this->render('devis/checkout', [
            'cart' => $cart,
            'total' => $total,
            'pageTitle' => 'DEVIS',
            'pageCss' => 'devis-checkout.css',
            'backUrl' => route('panier'),
        ], 'devis');
    }

    public function store(): void
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            redirect(route('panier'));
            return;
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $idClient = $this->currentClientId();
        $reference = 'DEV-' . date('YmdHis');
        $dateEvenement = $_POST['date_evenement'] ?? null;
        $messageClient = trim($_POST['message_client'] ?? '');

        try {
            $this->pdo->beginTransaction();

            $idDevis = $this->devisModel->create([
                'id_client' => $idClient,
                'reference' => $reference,
                'statut' => 'en_attente',
                'date_evenement' => $dateEvenement ?: null,
                'message_client' => $messageClient ?: null,
                'montant_total' => $total,
            ]);

            foreach ($cart as $item) {
                $this->devisLigneModel->create([
                    'id_devis' => $idDevis,
                    'id_prestation' => $item['prestation_id'],
                    'quantite' => $item['quantity'],
                    'prix_unitaire' => $item['price'],
                    'montant_ligne' => $item['price'] * $item['quantity'],
                ]);
            }

            $this->pdo->commit();
            $this->clearCart();
            redirect(route('devis_success', ['id' => $idDevis]));
            return;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            http_response_code(500);
            echo 'Erreur lors de l\'enregistrement du devis.';
        }
    }

    public function success(int $id): void
    {
        $devis = $this->devisModel->findById($id);

        if (!$devis) {
            http_response_code(404);
            echo 'Devis introuvable';
            return;
        }

        $lignes = $this->devisLigneModel->findByDevisId($id);
        $facture = $this->factureModel->findByDevisId($id);

        $this->render('devis/success', [
            'devis' => $devis,
            'lignes' => $lignes,
            'facture' => $facture,
            'pageTitle' => 'DEVIS',
            'pageCss' => 'devis-success.css',
            'backUrl' => route('devis_index'),
        ], 'devis');
    }

    public function index(): void
    {
        $idClient = $this->currentClientId();
        $devisList = $this->devisModel->findByClientId($idClient);

        $this->render('devis/index', [
            'devisList' => $devisList,
            'pageTitle' => 'MES DEVIS',
            'pageCss' => 'devis-index.css',
            'backUrl' => route('account'),
        ], 'devis');
    }

    public function show(int $id): void
    {
        $devis = $this->devisModel->findById($id);

        if (!$devis) {
            http_response_code(404);
            echo 'Devis introuvable';
            return;
        }

        $lignes = $this->devisLigneModel->findByDevisId($id);
        $facture = $this->factureModel->findByDevisId($id);

        $this->render('devis/show', [
            'devis' => $devis,
            'lignes' => $lignes,
            'facture' => $facture,
            'pageTitle' => 'DEVIS',
            'pageCss' => 'devis-show.css',
            'backUrl' => route('devis_index'),
        ], 'devis');
    }

    public function validate(int $id): void
    {
        $sessionClient = $_SESSION['client'] ?? null;

        if (!$sessionClient) {
            redirect(route('login'));
            return;
        }

        $devis = $this->devisModel->findById($id);
        if (!$devis) {
            http_response_code(404);
            echo 'Devis introuvable';
            return;
        }

        if ((int) $devis['id_client'] !== (int) $sessionClient['id_client']) {
            http_response_code(403);
            echo 'Acces refuse';
            return;
        }

        $existingFacture = $this->factureModel->findByDevisId($id);
        $pendingInvoiceData = [
            'id_devis' => $id,
            'reference' => $existingFacture['reference'] ?? ('FAC-' . $devis['reference']),
            'statut' => 'en_attente_validation',
            'montant_ttc' => (float) $devis['montant_total'],
            'date_emission' => date('Y-m-d'),
            'date_echeance' => date('Y-m-d', strtotime('+15 days')),
            'date_paiement' => null,
            'date_envoi_mail' => null,
        ];

        if (!$existingFacture) {
            $this->factureModel->create([
                'id_devis' => $pendingInvoiceData['id_devis'],
                'reference' => $pendingInvoiceData['reference'],
                'statut' => $pendingInvoiceData['statut'],
                'montant_ttc' => $pendingInvoiceData['montant_ttc'],
                'date_emission' => $pendingInvoiceData['date_emission'],
                'date_echeance' => $pendingInvoiceData['date_echeance'],
                'date_paiement' => $pendingInvoiceData['date_paiement'],
                'date_envoi_mail' => $pendingInvoiceData['date_envoi_mail'],
            ]);
        } else {
            $this->factureModel->update((int) $existingFacture['id_facture'], $pendingInvoiceData);
        }

        $this->devisModel->updateStatus($id, 'valide_client');
        redirect(route('devis_success', ['id' => $id]));
    }
}
