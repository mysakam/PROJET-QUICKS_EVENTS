<?php

class DashBoard extends Controller
{
    public function index(): void
    {
        if (Auth::check()) {
            redirect(route('dashboard'));
            return;
        }

        redirect(route('login'));
    }

    public function login(): void
    {
        if (Auth::check()) {
            redirect(route('dashboard'));
            return;
        }

        $this->render('auth/login', ['pageTitle' => 'Connexion back']);
    }

    public function authenticate(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!Auth::attempt($email, $password)) {
            Session::flash('error', 'Identifiants invalides.');
            redirect(route('login'));
            return;
        }

        if (!Auth::isAdmin()) {
            Auth::logout();
            Session::flash('error', 'Compte non autorisé sur le back-office.');
            redirect(route('login'));
            return;
        }

        redirect(route('dashboard'));
    }

    public function logout(): void
    {
        Auth::logout();
        redirect(route('login'));
    }

    public function dashboard(): void
    {
        $stats = [
            'clients' => $this->countTable('clients'),
            'devis' => $this->countTable('devis'),
            'prestations' => $this->countTable('prestations'),
            'factures' => $this->countTable('factures'),
        ];

        $this->render('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'user' => Auth::user(),
        ]);
    }

    private function countTable(string $table): int
    {
        try {
            $stmt = Database::getPdo()->query('SELECT COUNT(*) AS total FROM ' . $table);
            $row = $stmt->fetch();
            return (int) ($row['total'] ?? 0);
        } catch (Throwable) {
            return 0;
        }
    }
}
