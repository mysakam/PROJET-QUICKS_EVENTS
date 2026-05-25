<?php

class DevisController extends Controller
{
    private function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    private function redirectTo(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public function checkout(): void
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            $this->redirectTo(route('panier'));
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
            $this->redirectTo(route('panier'));
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $devis = [
            'id' => time(),
            'reference' => 'DEV-' . date('YmdHis'),
            'status' => 'en_attente',
            'total_amount' => $total,
            'event_date' => $_POST['event_date'] ?? null,
            'client_message' => $_POST['client_message'] ?? null,
            'items' => $cart,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $_SESSION['last_devis'] = $devis;

        $this->saveCart([]);
        $this->redirectTo(route('devis_success', ['id' => $devis['id']]));
    }

    /**
     * Vide ou met à jour le panier dans la session
     */
    private function saveCart(array $cart): void
    {
        $_SESSION['cart'] = $cart;
    }

    public function success(int $id): void
    {
        $devis = $_SESSION['last_devis'] ?? null;

        if (!$devis || (int) $devis['id'] !== $id) {
            http_response_code(404);
            echo 'Devis introuvable';
            return;
        }

        $this->render('devis/success', [
            'devis' => $devis
        ]);
    }

    public function index(): void
    {
        $devis = $_SESSION['last_devis'] ?? null;
        $devisList = $devis ? [$devis] : [];

        $this->render('devis/index', [
            'devisList' => $devisList
        ]);
    }

    public function show(int $id): void
    {
        $devis = $_SESSION['last_devis'] ?? null;

        if (!$devis || (int) $devis['id'] !== $id) {
            http_response_code(404);
            echo 'Devis introuvable';
            return;
        }

        $this->render('devis/show', [
            'devis' => $devis
        ]);
    }
}
