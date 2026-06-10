<?php
require_once __DIR__ . '/../models/ClientModel.php';

class AuthController extends Controller
{
    private ClientModel $clientModel;
    private DevisModel $devisModel;
    private FactureModel $factureModel;
    private array $adminEmails = ['samy@test.com'];

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->devisModel = new DevisModel();
        $this->factureModel = new FactureModel();
    }

    private function currentLang(): string
    {
        return (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr';
    }

    public function login(): void
    {
        $oldLoginEmail = $_SESSION['old_login_email'] ?? '';
        unset($_SESSION['old_login_email']);

        $this->render('auth/login', [
            'pageTitle' => 'Connexion',
            'lang' => $this->currentLang(),
            'oldLoginEmail' => $oldLoginEmail,
        ], 'auth');
    }

    public function register(): void
    {
        $oldRegister = $_SESSION['old_register'] ?? [];
        unset($_SESSION['old_register']);

        $this->render('auth/register', [
            'pageTitle' => 'Inscription',
            'lang' => $this->currentLang(),
            'oldRegister' => $oldRegister,
        ], 'auth');
    }

    public function store(): void
    {
        $langQuery = '?lang=' . $this->currentLang();
        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'telephone' => trim($_POST['telephone'] ?? ''),
        ];

        if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '' || $data['password'] === '') {
            $_SESSION['old_register'] = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
            ];
            $_SESSION['error'] = 'Nom, prenom, email et mot de passe sont obligatoires.';
            redirect(route('register') . $langQuery);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['old_register'] = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
            ];
            $_SESSION['error'] = 'Email invalide.';
            redirect(route('register') . $langQuery);
            return;
        }

        if ($data['password'] !== $data['password_confirm']) {
            $_SESSION['old_register'] = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
            ];
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            redirect(route('register') . $langQuery);
            return;
        }

        if (strlen($data['password']) < 6) {
            $_SESSION['old_register'] = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
            ];
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caracteres.';
            redirect(route('register') . $langQuery);
            return;
        }

        if ($this->clientModel->findByEmail($data['email'])) {
            $_SESSION['old_register'] = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
            ];
            $_SESSION['error'] = 'Cet email existe deja.';
            redirect(route('register') . $langQuery);
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
            'is_admin' => in_array(strtolower($data['email']), $this->adminEmails, true),
        ];
        unset($_SESSION['old_register']);

        $_SESSION['success'] = 'Compte client cree avec succes.';
        redirect(route('catalogues') . $langQuery);
        return;
    }
    public function authenticate(): void
    {
        $langQuery = '?lang=' . $this->currentLang();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $client = $this->clientModel->findByEmail($email);

        if (!$client || !password_verify($password, $client['mot_de_passe'])) {
            $_SESSION['old_login_email'] = $email;
            $_SESSION['error'] = 'Identifiants invalides';
            redirect(route('login') . $langQuery);
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
        unset($_SESSION['old_login_email']);

        redirect(route('catalogues') . $langQuery);
        return;
    }


    public function logout(): void
    {
        unset($_SESSION['client']);
        redirect(route('login') . '?lang=' . $this->currentLang());
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

        $idClient = (int) $sessionClient['id_client'];
        $devisList = $this->devisModel->findByClientId($idClient);
        $factures = $this->factureModel->findByClientId($idClient);
        $facturesByDevisId = [];

        foreach ($factures as $facture) {
            $facturesByDevisId[(int) ($facture['id_devis'] ?? 0)] = $facture;
        }

        $this->render('client/account', [
            'client' => $client,
            'devisList' => $devisList,
            'facturesByDevisId' => $facturesByDevisId,
            'pageTitle' => 'Mon compte',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
        ]);
    }
}
