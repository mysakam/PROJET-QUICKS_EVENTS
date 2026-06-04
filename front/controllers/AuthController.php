<?php
require_once __DIR__ . '/../models/ClientModel.php';

class AuthController extends Controller
{
    private ClientModel $clientModel;
    private array $adminEmails = ['samy@test.com'];

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function login(): void
    {
        $this->render('auth/login', [
            'pageTitle' => 'Connexion',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }

    public function register(): void
    {
        $this->render('auth/register', [
            'pageTitle' => 'Inscription',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }

    public function store(): void
    {
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'telephone' => trim($_POST['telephone'] ?? ''),
            'type_evenement' => trim($_POST['type_evenement'] ?? ''),
            'nb_personnes' => trim($_POST['nb_personnes'] ?? ''),
            'budget' => trim($_POST['budget'] ?? ''),
        ];

        if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '' || $data['password'] === '') {
            $_SESSION['error'] = 'Nom, prenom, email et mot de passe sont obligatoires.';
            redirect(route('register'));
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide.';
            redirect(route('register'));
            return;
        }

        if ($data['password'] !== $data['password_confirm']) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            redirect(route('register'));
            return;
        }

        if (strlen($data['password']) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caracteres.';
            redirect(route('register'));
            return;
        }

        if ($this->clientModel->findByEmail($data['email'])) {
            $_SESSION['error'] = 'Cet email existe deja.';
            redirect(route('register'));
            return;
        }

        $clientId = $this->clientModel->createWithProfile($data);

        session_regenerate_id(true);
        $_SESSION['client'] = [
            'id_client' => $clientId,
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'type_evenement' => $data['type_evenement'],
            'nb_personnes' => $data['nb_personnes'],
            'budget' => $data['budget'],
            'is_admin' => in_array(strtolower($data['email']), $this->adminEmails, true),
        ];

        $_SESSION['success'] = 'Compte client cree avec succes.';
        redirect(route('catalogues'));
        return;
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
            'is_admin' => in_array(strtolower($client['email']), $this->adminEmails, true),
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
        $sessionClient = $_SESSION['client'] ?? null;

        if (!$sessionClient) {
            redirect(route('login'));
            return;
        }

        $client = $this->clientModel->findById((int) $sessionClient['id_client']);

        if (!$client) {
            unset($_SESSION['client']);
            redirect(route('login'));
            return;
        }

        $this->render('client/account', [
            'client' => $client,
            'pageTitle' => 'Mon compte',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }
}
