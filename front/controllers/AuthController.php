<?php
require_once __DIR__ . '/../models/ClientModel.php';

class AuthController extends Controller
{
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function login(): void
    {
        $this->render('auth/login');
    }

    public function register(): void
    {
        $this->render('auth/register');
    }
    public function authenticate(): void
{
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $client = $this->clientModel->findByEmail($email);

    if (!$client || !password_verify($password, $client['mot_de_passe'])) {
        $_SESSION['error'] = 'Identifiants invalides';
        redirect(route('login'));
        return;
    }

    session_regenerate_id(true);

    $_SESSION['client'] = [
        'id_client' => $client['id_client'],
        'nom' => $client['nom'],
        'prenom' => $client['prenom'],
        'email' => $client['email'],
    ];

    redirect(route('catalogues'));
    return;
}
    

    public function logout(): void
    {
        unset($_SESSION['client']);
        redirect(route('login'));
        return;
    }

    public function account(): void
    {
        $client = $_SESSION['client'] ?? null;

        if (!$client) {
            redirect(route('login'));
            return;
        }

        $this->render('client/account', compact('client'));
    }
}