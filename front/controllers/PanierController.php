<?php

class PanierController extends Controller
{
    private function ensureEventRequestCompleted(): bool
    {
        $eventRequest = $_SESSION['event_request'] ?? [];
        $typeEvenement = trim((string) ($eventRequest['type_evenement'] ?? ''));
        $nbPersonnes = trim((string) ($eventRequest['nb_personnes'] ?? ''));
        $budget = trim((string) ($eventRequest['budget'] ?? ''));

        if ($typeEvenement !== '' && $nbPersonnes !== '' && $budget !== '') {
            return true;
        }

        $_SESSION['error'] = "Veuillez d'abord renseigner le formulaire de creation d'un evenement avant de constituer votre panier.";
        redirect(route('mon_evenement'));
        return false;
    }

    private function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    private function saveCart(array $cart): void
    {
        $_SESSION['cart'] = $cart;
    }

    public function index(): void
    {
        if (!$this->ensureEventRequestCompleted()) {
            return;
        }

        $cart = $this->getCart();
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        $this->render('panier/index', compact('cart', 'total'));
    }

    public function add(int $id): void
    {
        if (!$this->ensureEventRequestCompleted()) {
            return;
        }

        $prestationModel = new PrestationModel();
        $prestation = $prestationModel->findById($id);

        if (!$prestation) {
            redirect(route('catalogues'));
            return;
        }

        $cart = $this->getCart();

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'prestation_id' => $prestation['id_prestation'],
                'name' => $prestation['nom'],
                'category' => $prestation['category_name'],
                'price' => $prestation['prix_unitaire'],
                'quantity' => 1
            ];
        }

        $this->saveCart($cart);
        redirect(route('panier'));
    }

    public function remove(int $id): void
    {
        if (!$this->ensureEventRequestCompleted()) {
            return;
        }

        $cart = $this->getCart();

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $this->saveCart($cart);
        redirect(route('panier'));
    }

    public function clear(): void
    {
        if (!$this->ensureEventRequestCompleted()) {
            return;
        }

        unset($_SESSION['cart']);
        redirect(route('panier'));
    }
}
