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

    private function redirectTo(string $routeName): void
    {
        redirect(route($routeName));
    }

    public function index(): void
    {
        $cart = $this->getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->render('panier/index', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function add(int $id): void
    {
        $prestationModel = new PrestationModel();
        $prestation = $prestationModel->findById($id);

        if (!$prestation) {
            $this->redirectTo('catalogues');
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
        $this->redirectTo('panier');
    }

    public function remove(int $id): void
    {
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        $this->saveCart($cart);
        $this->redirectTo('panier');
    }

    public function clear(): void
    {
        unset($_SESSION['cart']);
        $this->redirectTo('panier');
    }
}