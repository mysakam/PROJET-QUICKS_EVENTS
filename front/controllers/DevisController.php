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

    private function generateReference(): string
    {
        return 'DEV-' . date('YmdHis');
    }

    public function checkout(): void
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            redirect(route('panier'));
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->render('devis/checkout', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function store(): void
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            redirect(route('panier'));
        }

        $dateEvenement = $_POST['date_evenement'] ?? null;
        $messageClient = trim($_POST['message_client'] ?? '');

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $idClient = 1;
        $reference = $this->generateReference();

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
            'lignes' => $lignes
        ]);
    }

    public function index(): void
    {
        $idClient = 1;
        $devisList = $this->devisModel->findByClientId($idClient);

        $this->render('devis/index', [
            'devisList' => $devisList
        ]);
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
            'lignes' => $lignes
        ]);
    }
}