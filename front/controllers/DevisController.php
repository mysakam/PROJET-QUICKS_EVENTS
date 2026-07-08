<?php

class DevisController extends Controller
{
    private DevisModel $devisModel;
    private DevisLigneModel $devisLigneModel;
    private FactureModel $factureModel;
    private NotificationModel $notificationModel;
    private PDO $pdo;

    public function __construct()
    {
        $this->devisModel = new DevisModel();
        $this->devisLigneModel = new DevisLigneModel();
        $this->factureModel = new FactureModel();
        $this->notificationModel = new NotificationModel();
        $this->pdo = Database::getPdo();
    }

    private function notifyAdmin(string $type, string $title, string $message, array $payload = []): void
    {
        try {
            $this->notificationModel->createForAdmin($type, $title, $message, $payload);
        } catch (Throwable $exception) {
            // Les notifications ne doivent pas bloquer le flux devis.
        }
    }

    private function currentClientId(): int
    {
        return (int) ($_SESSION['client']['id_client'] ?? 0);
    }

    private function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    private function clearCart(): void
    {
        unset($_SESSION['cart']);
    }

    private static function amountFromText(string $value): float
    {
        $digitsOnly = preg_replace('/[^0-9.,]/', '', $value) ?? '';
        $normalized = str_replace(' ', '', $digitsOnly);
        $normalized = str_replace(',', '.', $normalized);
        return (float) $normalized;
    }

    private static function parseEventSummary(?string $message): array
    {
        $summary = [
            'event_request' => [],
            'selected_package' => null,
        ];

        if ($message === null || trim($message) === '') {
            return $summary;
        }

        $packageTheme = null;
        $packagePrice = null;

        foreach (preg_split('/\R+/', $message) as $rawLine) {
            $line = trim($rawLine);

            if (stripos($line, 'Type :') === 0) {
                $summary['event_request']['type_evenement'] = trim(substr($line, strlen('Type :')));
                continue;
            }

            if (stripos($line, "Nombre d'invites :") === 0) {
                $summary['event_request']['nb_personnes'] = trim(substr($line, strlen("Nombre d'invites :")));
                continue;
            }

            if (stripos($line, 'Budget estimatif :') === 0) {
                $summary['event_request']['budget'] = trim(substr($line, strlen('Budget estimatif :')));
                continue;
            }

            if (stripos($line, 'Package choisi :') === 0) {
                $packageTheme = trim(substr($line, strlen('Package choisi :')));
                continue;
            }

            if (stripos($line, 'Prix package :') === 0) {
                $packagePrice = trim(substr($line, strlen('Prix package :')));
                continue;
            }
        }

        if ($packageTheme !== null && $packageTheme !== '') {
            $summary['selected_package'] = [
                'theme' => $packageTheme,
                'price' => $packagePrice,
            ];

            if (empty($summary['event_request']['type_evenement'])) {
                $summary['event_request']['type_evenement'] = $packageTheme;
            }

            if ($packagePrice !== null && $packagePrice !== '' && empty($summary['event_request']['budget'])) {
                $summary['event_request']['budget'] = $packagePrice;
            }
        }

        return $summary;
    }

    public function checkout(): void
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            redirect(route('panier'));
            return;
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        $this->render('devis/checkout', [
            'cart' => $cart,
            'total' => $total,
            'eventRequest' => $_SESSION['event_request'] ?? [],
            'oldDevisForm' => $_SESSION['old_devis_form'] ?? [],
            'pageTitle' => 'DEVIS PROPOSE',
            'pageCss' => 'devis-checkout.css',
            'backUrl' => route('panier'),
        ], 'devis');

        unset($_SESSION['old_devis_form']);
    }

    public function eventRequest(): void
    {
        if (empty($_SESSION['client'])) {
            redirect(route('login'));
            return;
        }

        $this->render('devis/event', [
            'pageTitle' => 'Créer mon événement',
            'lang' => (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr',
            'eventRequest' => $_SESSION['event_request'] ?? [],
            'oldEventRequest' => $_SESSION['old_event_request'] ?? [],
        ], 'auth');
    }

    public function eventRequestStore(): void
    {
        if (empty($_SESSION['client'])) {
            redirect(route('login'));
            return;
        }

        $langQuery = '?lang=' . ((($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr');
        $data = [
            'type_evenement' => trim($_POST['type_evenement'] ?? ''),
            'nb_personnes' => trim($_POST['nb_personnes'] ?? ''),
            'budget' => trim($_POST['budget'] ?? ''),
        ];

        if ($data['type_evenement'] === '' || $data['nb_personnes'] === '' || $data['budget'] === '') {
            $_SESSION['old_event_request'] = $data;
            $_SESSION['error'] = 'Merci de renseigner le type d\'evenement, le nombre d\'invites et le budget estimatif.';
            redirect(route('mon_evenement') . $langQuery);
            return;
        }

        $_SESSION['event_request'] = $data;
        unset($_SESSION['old_event_request']);
        $_SESSION['success'] = 'Votre fiche evenement a ete enregistree.';

        if (!empty($this->getCart())) {
            redirect(route('devis_checkout') . $langQuery);
            return;
        }

        redirect(route('catalogues') . $langQuery);
    }

    public function store(): void
    {
        $cart = $this->getCart();
        $langQuery = '?lang=' . ((($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr');

        if (empty($cart)) {
            redirect(route('panier'));
            return;
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $idClient = $this->currentClientId();
        $reference = 'DEV-' . date('YmdHis');
        $dateEvenement = trim($_POST['date_evenement'] ?? '');
        $messageClient = trim($_POST['message_client'] ?? '');
        $eventRequest = $_SESSION['event_request'] ?? [];
        $selectedPackage = $_SESSION['selected_package'] ?? null;
        $eventSummary = [];

        if ($dateEvenement === '') {
            $_SESSION['error'] = "La date de l'evenement est obligatoire pour enregistrer une proposition de devis.";
            $_SESSION['old_devis_form'] = [
                'date_evenement' => '',
                'message_client' => $messageClient,
            ];
            redirect(route('devis_checkout') . $langQuery);
            return;
        }

        if (!empty($eventRequest)) {
            $eventSummary[] = 'FICHE EVENEMENT';

            if (!empty($eventRequest['type_evenement'])) {
                $eventSummary[] = 'Type : ' . $eventRequest['type_evenement'];
            }

            if (!empty($eventRequest['nb_personnes'])) {
                $eventSummary[] = 'Nombre d\'invites : ' . $eventRequest['nb_personnes'];
            }

            if (!empty($eventRequest['budget'])) {
                $eventSummary[] = 'Budget estimatif : ' . $eventRequest['budget'];
            }
        }

        if ($messageClient !== '') {
            $eventSummary[] = 'Message client : ' . $messageClient;
        }

        if (!empty($selectedPackage['theme'])) {
            $eventSummary[] = 'Package choisi : ' . $selectedPackage['theme'];
            if (!empty($selectedPackage['price'])) {
                $eventSummary[] = 'Prix package : ' . $selectedPackage['price'];
            }
        }

        $combinedMessage = !empty($eventSummary) ? implode("\n", $eventSummary) : null;

        try {
            $this->pdo->beginTransaction();

            $idDevis = $this->devisModel->create([
                'id_client' => $idClient,
                'reference' => $reference,
                'statut' => 'en_attente',
                'date_evenement' => $dateEvenement,
                'message_client' => $combinedMessage,
                'montant_total' => $total,
            ]);

            foreach ($cart as $item) {
                if (!empty($item['is_package']) || empty($item['prestation_id'])) {
                    continue;
                }

                $this->devisLigneModel->create([
                    'id_devis' => $idDevis,
                    'id_prestation' => $item['prestation_id'],
                    'quantite' => $item['quantity'],
                    'prix_unitaire' => $item['price'],
                    'montant_ligne' => $item['price'] * $item['quantity'],
                ]);
            }

            $this->pdo->commit();
            $this->clearCart();
            unset($_SESSION['event_request'], $_SESSION['old_event_request'], $_SESSION['selected_package']);

            $client = $_SESSION['client'] ?? [];
            $this->notifyAdmin(
                'devis_created',
                'Nouveau devis client',
                'Un nouveau devis a ete cree par ' . trim((string) ($client['prenom'] ?? '')) . ' ' . trim((string) ($client['nom'] ?? '')) . ' (' . ($client['email'] ?? 'client inconnu') . ')' .
                    ' - reference ' . $reference,
                [
                    'id_devis' => $idDevis,
                    'id_client' => $idClient,
                    'reference' => $reference,
                    'montant_total' => $total,
                    'statut' => 'en_attente',
                    'target_url' => route('admin_devis_show', ['id' => $idDevis]) . '?lang=' . current_lang(),
                ]
            );

            $_SESSION['success'] = 'Votre proposition de devis a bien ete enregistree.';
            redirect(route('devis_success', ['id' => $idDevis]) . $langQuery);
            return;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            http_response_code(500);
            echo 'Erreur lors de l\'enregistrement de la proposition de devis.';
        }
    }

    public function success(int $id): void
    {
        $devis = $this->devisModel->findById($id);

        if (!$devis) {
            http_response_code(404);
            echo 'Proposition de devis introuvable';
            return;
        }

        if ((int) ($devis['id_client'] ?? 0) !== $this->currentClientId()) {
            http_response_code(403);
            echo 'Acces refuse';
            return;
        }

        $lignes = $this->devisLigneModel->findByDevisId($id);
        $facture = $this->factureModel->findByDevisId($id);

        $this->render('devis/success', [
            'devis' => $devis,
            'lignes' => $lignes,
            'facture' => $facture,
            'pageTitle' => 'DEVIS PROPOSE',
            'pageCss' => 'devis-success.css',
            'backUrl' => route('devis_index'),
        ], 'devis');
    }

    public function index(): void
    {
        $idClient = $this->currentClientId();
        $devisList = $this->devisModel->findByClientId($idClient);

        $this->render('devis/index', [
            'devisList' => $devisList,
            'pageTitle' => 'LES DEVIS PROPOSES',
            'pageCss' => 'devis-index.css',
            'backUrl' => route('account'),
        ], 'devis');
    }

    public function factures(): void
    {
        $idClient = $this->currentClientId();
        $factures = $this->factureModel->findByClientId($idClient);

        $this->render('devis/factures', [
            'factures' => $factures,
            'pageTitle' => 'MES FACTURES',
            'pageCss' => 'devis-index.css',
            'backUrl' => route('account'),
        ], 'devis');
    }

    public function show(int $id): void
    {
        $devis = $this->devisModel->findById($id);

        if (!$devis) {
            http_response_code(404);
            echo 'Proposition de devis introuvable';
            return;
        }

        if ((int) ($devis['id_client'] ?? 0) !== $this->currentClientId()) {
            http_response_code(403);
            echo 'Acces refuse';
            return;
        }

        $lignes = $this->devisLigneModel->findByDevisId($id);
        $facture = $this->factureModel->findByDevisId($id);

        $this->render('devis/show', [
            'devis' => $devis,
            'lignes' => $lignes,
            'facture' => $facture,
            'pageTitle' => 'DEVIS PROPOSE',
            'pageCss' => 'devis-show.css',
            'backUrl' => route('devis_index'),
        ], 'devis');
    }

    public function validate(int $id): void
    {
        $sessionClient = $_SESSION['client'] ?? null;
        $langQuery = '?lang=' . ((($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr');

        if (!$sessionClient) {
            redirect(route('login'));
            return;
        }

        $devis = $this->devisModel->findById($id);
        if (!$devis) {
            http_response_code(404);
            echo 'Proposition de devis introuvable';
            return;
        }

        if ((int) $devis['id_client'] !== (int) $sessionClient['id_client']) {
            http_response_code(403);
            echo 'Acces refuse';
            return;
        }

        $existingFacture = $this->factureModel->findByDevisId($id);
        $pendingInvoiceData = [
            'id_devis' => $id,
            'reference' => $existingFacture['reference'] ?? ('FAC-' . $devis['reference']),
            'statut' => 'en_attente_validation',
            'montant_ttc' => (float) $devis['montant_total'],
            'date_emission' => date('Y-m-d'),
            'date_echeance' => date('Y-m-d', strtotime('+15 days')),
            'date_paiement' => null,
            'date_envoi_mail' => null,
        ];

        if (!$existingFacture) {
            $this->factureModel->create([
                'id_devis' => $pendingInvoiceData['id_devis'],
                'reference' => $pendingInvoiceData['reference'],
                'statut' => $pendingInvoiceData['statut'],
                'montant_ttc' => $pendingInvoiceData['montant_ttc'],
                'date_emission' => $pendingInvoiceData['date_emission'],
                'date_echeance' => $pendingInvoiceData['date_echeance'],
                'date_paiement' => $pendingInvoiceData['date_paiement'],
                'date_envoi_mail' => $pendingInvoiceData['date_envoi_mail'],
            ]);
        } else {
            $this->factureModel->update((int) $existingFacture['id_facture'], $pendingInvoiceData);
        }

        $this->devisModel->updateStatus($id, 'valide_client');

        $client = $_SESSION['client'] ?? [];
        $this->notifyAdmin(
            'devis_validated',
            'Devis valide par le client',
            'Le client ' . trim((string) ($client['prenom'] ?? '')) . ' ' . trim((string) ($client['nom'] ?? '')) . ' a valide le devis ' . ($devis['reference'] ?? ('#' . $id)) . '.',
            [
                'id_devis' => $id,
                'id_client' => (int) $sessionClient['id_client'],
                'reference' => $devis['reference'] ?? null,
                'statut' => 'valide_client',
                'target_url' => route('admin_devis_show', ['id' => $id]) . '?lang=' . current_lang(),
            ]
        );

        redirect(route('devis_success', ['id' => $id]) . $langQuery);
    }

    public function cancel(int $id): void
    {
        $sessionClient = $_SESSION['client'] ?? null;
        $langQuery = '?lang=' . ((($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr');

        if (!$sessionClient) {
            redirect(route('login'));
            return;
        }

        $devis = $this->devisModel->findById($id);
        if (!$devis) {
            http_response_code(404);
            echo 'Proposition de devis introuvable';
            return;
        }

        if ((int) $devis['id_client'] !== (int) $sessionClient['id_client']) {
            http_response_code(403);
            echo 'Acces refuse';
            return;
        }

        $this->devisModel->deleteByClient($id, (int) $sessionClient['id_client']);
        $_SESSION['success'] = 'La proposition de devis a bien ete annulee.';
        redirect(route('devis_index') . $langQuery);
    }

    public function reopen(int $id): void
    {
        $sessionClient = $_SESSION['client'] ?? null;
        $langQuery = '?lang=' . ((($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr');

        if (!$sessionClient) {
            redirect(route('login'));
            return;
        }

        $devis = $this->devisModel->findById($id);
        if (!$devis) {
            http_response_code(404);
            echo 'Proposition de devis introuvable';
            return;
        }

        if ((int) $devis['id_client'] !== (int) $sessionClient['id_client']) {
            http_response_code(403);
            echo 'Acces refuse';
            return;
        }

        $lignes = $this->devisLigneModel->findByDevisId($id);
        $prestationModel = new PrestationModel();
        $cart = [];

        foreach ($lignes as $ligne) {
            $idPrestation = (int) ($ligne['id_prestation'] ?? 0);
            if ($idPrestation <= 0) {
                continue;
            }

            $prestation = $prestationModel->findById($idPrestation);
            $name = $prestation['nom'] ?? ($ligne['nom'] ?? ('Prestation #' . $idPrestation));
            $category = $prestation['category_name'] ?? 'Prestation';

            $cart[$idPrestation] = [
                'prestation_id' => $idPrestation,
                'name' => $name,
                'category' => $category,
                'price' => (float) ($ligne['prix_unitaire'] ?? 0),
                'quantity' => (int) ($ligne['quantite'] ?? 1),
            ];
        }

        $parsedSummary = self::parseEventSummary($devis['message_client'] ?? null);
        $selectedPackage = $parsedSummary['selected_package'];

        if (empty($cart) && !empty($selectedPackage['theme'])) {
            $packageKey = (int) (950000 + (abs(crc32((string) $selectedPackage['theme'])) % 49999));
            $cart[$packageKey] = [
                'prestation_id' => null,
                'name' => 'Package - ' . $selectedPackage['theme'],
                'category' => 'Package evenementiel',
                'price' => self::amountFromText((string) ($selectedPackage['price'] ?? '0')),
                'quantity' => 1,
                'is_package' => true,
                'package_theme' => $selectedPackage['theme'],
            ];
        }

        $_SESSION['cart'] = $cart;

        if (!empty($parsedSummary['event_request'])) {
            $_SESSION['event_request'] = $parsedSummary['event_request'];
        }

        if (!empty($selectedPackage)) {
            $_SESSION['selected_package'] = $selectedPackage;
        }

        $_SESSION['success'] = 'Un nouveau panier a ete genere a partir de la proposition de devis.';

        if (empty($cart)) {
            redirect(route('mon_evenement') . $langQuery);
            return;
        }

        $client = $_SESSION['client'] ?? [];
        $this->notifyAdmin(
            'devis_modified',
            'Devis modifie par le client',
            'Le client ' . trim((string) ($client['prenom'] ?? '')) . ' ' . trim((string) ($client['nom'] ?? '')) . ' a repris et modifie son devis ' . ($devis['reference'] ?? ('#' . $id)) . '.',
            [
                'id_devis' => $id,
                'id_client' => (int) $sessionClient['id_client'],
                'reference' => $devis['reference'] ?? null,
                'source' => 'reopen',
                'target_url' => route('admin_devis_show', ['id' => $id]) . '?lang=' . current_lang(),
            ]
        );

        redirect(route('devis_checkout') . $langQuery);
    }
}
