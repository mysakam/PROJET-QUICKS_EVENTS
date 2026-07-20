<?php

class DashBoard extends Controller
{
    private function loginFailureMessage(?string $reason): string
    {
        return match ($reason) {
            'missing_credentials' => 'Identifiants invalides. Motif: email et mot de passe obligatoires.',
            'account_not_found' => 'Identifiants invalides. Motif: aucun compte trouvé pour cet email.',
            'invalid_password' => 'Identifiants invalides. Motif: mot de passe incorrect.',
            'storage_error' => 'Connexion impossible. Motif: lecture du compte administrateur en échec.',
            default => 'Identifiants invalides.',
        };
    }

    public function index(): void
    {
        if (Auth::check()) {
            redirect(route('dashboard'));
            return;
        }

        redirect(route('admin_login'));
    }

    public function login(): void
    {
        if (Auth::check()) {
            redirect(route('dashboard'));
            return;
        }

        $this->render('auth/admin-login', ['pageTitle' => 'Connexion administrateur']);
    }

    public function authenticate(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!Auth::attempt($email, $password)) {
            Session::flash('error', $this->loginFailureMessage(Auth::failureReason()));
            Session::flash('old_email', $email);
            redirect(route('admin_login'));
            return;
        }

        if (!Auth::isAdmin()) {
            Auth::logout();
            Session::flash('error', 'Connexion refusée. Motif: ce compte n’est pas autorisé sur le back-office.');
            Session::flash('old_email', $email);
            redirect(route('admin_login'));
            return;
        }

        redirect(route('dashboard'));
    }

    public function logout(): void
    {
        Auth::logout();
        redirect(route('admin_login'));
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
