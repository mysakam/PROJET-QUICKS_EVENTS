<?php

class PanierController extends Controller
{
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
        $cart = $this->getCart();
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        $this->render('panier/index', compact('cart', 'total'));
    }

    public function add(int $id): void
    {
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
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $this->saveCart($cart);
        redirect(route('panier'));
    }

    public function clear(): void
    {
        unset($_SESSION['cart']);
        redirect(route('panier'));
    }
}