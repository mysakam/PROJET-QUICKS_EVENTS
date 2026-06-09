<?php

class AdminClientsController extends AdminBaseController
{
    private ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $searchQuery = trim($_GET['q'] ?? '');
        $clients = $this->clientModel->findAll();

        if ($searchQuery !== '') {
            $needle = mb_strtolower($searchQuery, 'UTF-8');
            $clients = array_values(array_filter($clients, static function (array $client) use ($needle): bool {
                $haystack = mb_strtolower(implode(' ', [
                    (string) ($client['nom'] ?? ''),
                    (string) ($client['prenom'] ?? ''),
                    (string) ($client['email'] ?? ''),
                    (string) ($client['telephone'] ?? ''),
                ]), 'UTF-8');

                return str_contains($haystack, $needle);
            }));
        }

        $this->render('admin/clients/index', [
            'clients' => $clients,
            'searchQuery' => $searchQuery,
            'pageTitle' => 'Admin clients',
            'lang' => $this->getLang(),
        ]);
    }

    public function create(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('admin/clients/create', [
            'pageTitle' => 'Ajouter un client',
            'lang' => $this->getLang(),
        ]);
    }

    public function store(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'telephone' => trim($_POST['telephone'] ?? ''),
        ];

        if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '' || $data['password'] === '') {
            $_SESSION['error'] = 'Nom, prenom, email et mot de passe sont obligatoires.';
            redirect(route('admin_clients_create'));
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide.';
            redirect(route('admin_clients_create'));
            return;
        }

        if ($this->clientModel->findByEmail($data['email'])) {
            $_SESSION['error'] = 'Cet email existe deja.';
            redirect(route('admin_clients_create'));
            return;
        }

        $this->clientModel->createWithProfile($data);
        $_SESSION['success'] = 'Client ajoute avec succes.';
        redirect(route('admin_clients_index'));
    }

    public function edit(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $client = $this->clientModel->findById($id);
        if (!$client) {
            http_response_code(404);
            echo 'Client introuvable.';
            return;
        }

        $this->render('admin/clients/edit', [
            'client' => $client,
            'pageTitle' => 'Modifier un client',
            'lang' => $this->getLang(),
        ]);
    }

    public function update(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $existing = $this->clientModel->findById($id);
        if (!$existing) {
            http_response_code(404);
            echo 'Client introuvable.';
            return;
        }

        $data = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
        ];

        if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '') {
            $_SESSION['error'] = 'Nom, prenom et email sont obligatoires.';
            redirect(route('admin_clients_edit', ['id' => $id]));
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide.';
            redirect(route('admin_clients_edit', ['id' => $id]));
            return;
        }

        $emailOwner = $this->clientModel->findByEmail($data['email']);
        if ($emailOwner && (int) $emailOwner['id_client'] !== $id) {
            $_SESSION['error'] = 'Cet email appartient deja a un autre client.';
            redirect(route('admin_clients_edit', ['id' => $id]));
            return;
        }

        $this->clientModel->updateProfile($id, $data);
        $_SESSION['success'] = 'Client modifie avec succes.';
        redirect(route('admin_clients_index'));
    }

    public function delete(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->clientModel->delete($id);
        $_SESSION['success'] = 'Client supprime avec succes.';
        redirect(route('admin_clients_index'));
    }
}
