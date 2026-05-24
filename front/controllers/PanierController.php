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
        header('Location: ' . route($routeName));
        exit;
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
        $prestations = [
            1 => ['id' => 1, 'name' => 'Eden Park', 'category' => 'Mariage', 'price' => 8000],
            2 => ['id' => 2, 'name' => 'Wedding’s Life', 'category' => 'Mariage', 'price' => 6500],
            3 => ['id' => 3, 'name' => 'Happy Time', 'category' => 'Anniversaire', 'price' => 3000],
            4 => ['id' => 4, 'name' => 'Your Hero', 'category' => 'Séminaire', 'price' => 9000],
        ];

        if (!isset($prestations[$id])) {
            $this->redirectTo('catalogues');
        }

        $cart = $this->getCart();

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'prestataire_id' => $prestations[$id]['id'],
                'name' => $prestations[$id]['name'],
                'category' => $prestations[$id]['category'],
                'price' => $prestations[$id]['price'],
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
        $this->saveCart([]);
        $this->redirectTo('panier');
    }
}