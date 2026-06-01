<?php

class DevisController extends Controller
{
    private DevisModel $devisModel;
    private DevisLigneModel $devisLigneModel;
    private PDO $pdo;

    public function __construct()
    {
        $this->devisModel = new DevisModel();
        $this->devisLigneModel = new DevisLigneModel();
        $this->pdo = Database::getPdo();
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
        $idClient = 1;
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

        $this->render('devis/success', [
            'devis' => $devis,
            'lignes' => $lignes,
            'pageTitle' => 'DEVIS',
            'pageCss' => 'devis-success.css',
            'backUrl' => route('devis_index'),
        ], 'devis');
    }

    public function index(): void
    {
        $idClient = 1;
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

        $this->render('devis/show', [
            'devis' => $devis,
            'lignes' => $lignes,
            'pageTitle' => 'DEVIS',
            'pageCss' => 'devis-show.css',
            'backUrl' => route('devis_index'),
        ], 'devis');
    }
}
