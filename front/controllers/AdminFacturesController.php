<?php

class AdminFacturesController extends AdminBaseController
{
    private FactureModel $factureModel;

    public function __construct()
    {
        $this->factureModel = new FactureModel();
    }

    private function sanitizeAmount(string $value): float
    {
        $clean = str_replace(',', '.', trim($value));
        return (float) $clean;
    }

    private function makeReference(): string
    {
        return 'FAC-' . date('Ymd-His') . '-' . random_int(100, 999);
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('admin/factures/index', [
            'factures' => $this->factureModel->findAll(),
            'pageTitle' => 'Admin factures',
            'lang' => $this->getLang(),
        ]);
    }

    public function create(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('admin/factures/create', [
            'devisOptions' => $this->factureModel->findDevisWithoutFacture(),
            'defaultReference' => $this->makeReference(),
            'statuses' => $this->factureModel->statuses(),
            'pageTitle' => 'Ajouter une facture',
            'lang' => $this->getLang(),
        ]);
    }

    public function store(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $data = [
            'id_devis' => (int) ($_POST['id_devis'] ?? 0),
            'reference' => trim($_POST['reference'] ?? ''),
            'statut' => trim($_POST['statut'] ?? 'emise'),
            'montant_ttc' => $this->sanitizeAmount((string) ($_POST['montant_ttc'] ?? '0')),
            'date_emission' => trim($_POST['date_emission'] ?? ''),
            'date_echeance' => trim($_POST['date_echeance'] ?? ''),
            'date_paiement' => trim($_POST['date_paiement'] ?? ''),
        ];

        if ($data['reference'] === '') {
            $data['reference'] = $this->makeReference();
        }

        if (
            $data['id_devis'] <= 0 ||
            !in_array($data['statut'], $this->factureModel->statuses(), true) ||
            $data['montant_ttc'] < 0
        ) {
            $_SESSION['error'] = 'Champs facture invalides.';
            redirect(route('admin_factures_create'));
            return;
        }

        try {
            $this->factureModel->create($data);
            $_SESSION['success'] = 'Facture ajoutee avec succes.';
            redirect(route('admin_factures_index'));
            return;
        } catch (Throwable $e) {
            $_SESSION['error'] = 'Impossible de creer la facture (devis deja facture ou reference deja utilisee).';
            redirect(route('admin_factures_create'));
            return;
        }
    }

    public function edit(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $facture = $this->factureModel->findById($id);

        if (!$facture) {
            http_response_code(404);
            echo 'Facture introuvable.';
            return;
        }

        $this->render('admin/factures/edit', [
            'facture' => $facture,
            'statuses' => $this->factureModel->statuses(),
            'pageTitle' => 'Modifier une facture',
            'lang' => $this->getLang(),
        ]);
    }

    public function show(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $facture = $this->factureModel->findById($id);
        if (!$facture) {
            http_response_code(404);
            echo 'Facture introuvable.';
            return;
        }

        $this->render('admin/factures/show', [
            'facture' => $facture,
            'pageTitle' => 'Fiche facture',
            'lang' => $this->getLang(),
        ]);
    }

    public function update(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $existing = $this->factureModel->findById($id);
        if (!$existing) {
            http_response_code(404);
            echo 'Facture introuvable.';
            return;
        }

        $data = [
            'statut' => trim($_POST['statut'] ?? 'emise'),
            'montant_ttc' => $this->sanitizeAmount((string) ($_POST['montant_ttc'] ?? '0')),
            'date_emission' => trim($_POST['date_emission'] ?? ''),
            'date_echeance' => trim($_POST['date_echeance'] ?? ''),
            'date_paiement' => trim($_POST['date_paiement'] ?? ''),
        ];

        if (!in_array($data['statut'], $this->factureModel->statuses(), true) || $data['montant_ttc'] < 0) {
            $_SESSION['error'] = 'Champs facture invalides.';
            redirect(route('admin_factures_edit', ['id' => $id]));
            return;
        }

        $this->factureModel->update($id, $data);
        $_SESSION['success'] = 'Facture modifiee avec succes.';
        redirect(route('admin_factures_index'));
    }

    public function delete(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->factureModel->delete($id);
        $_SESSION['success'] = 'Facture supprimee avec succes.';
        redirect(route('admin_factures_index'));
    }

    public function sendMail(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $facture = $this->factureModel->findById($id);
        if (!$facture) {
            http_response_code(404);
            echo 'Facture introuvable.';
            return;
        }

        if (empty($facture['client_email'])) {
            $_SESSION['error'] = 'Aucun email client disponible pour cette facture.';
            redirect(route('admin_factures_show', ['id' => $id]));
            return;
        }

        $adminMessage = trim($_POST['admin_message'] ?? '');

        $subject = 'Votre facture QUICK\'EVENTS ' . $facture['reference'];
        $body = $this->buildMailBody($facture, $adminMessage);

        if (!send_html_mail($facture['client_email'], $subject, $body)) {
            $_SESSION['error'] = 'Envoi mail impossible pour le moment.';
            redirect(route('admin_factures_show', ['id' => $id]));
            return;
        }

        $this->factureModel->markAsSent($id);
        $_SESSION['success'] = 'Facture envoyee par mail avec succes.';
        redirect(route('admin_factures_show', ['id' => $id]));
    }

    private function buildMailBody(array $facture, string $adminMessage = ''): string
    {
        $clientName = trim(($facture['client_prenom'] ?? '') . ' ' . ($facture['client_nom'] ?? ''));
        $amount = number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ');
        $customMessage = '';

        if ($adminMessage !== '') {
            $customMessage = '<p><strong>Message de l\'administration :</strong><br>'
                . nl2br(htmlspecialchars($adminMessage, ENT_QUOTES, 'UTF-8'))
                . '</p>';
        }

        return '<h2>Bonjour ' . htmlspecialchars($clientName ?: 'client', ENT_QUOTES, 'UTF-8') . '</h2>'
            . '<p>Votre facture <strong>' . htmlspecialchars($facture['reference'], ENT_QUOTES, 'UTF-8') . '</strong> est prête.</p>'
            . '<p>Montant TTC : <strong>' . htmlspecialchars($amount, ENT_QUOTES, 'UTF-8') . ' EUR</strong></p>'
            . '<p>Statut : ' . htmlspecialchars($facture['statut'], ENT_QUOTES, 'UTF-8') . '</p>'
            . $customMessage
            . '<p>Merci de vérifier vos informations et de procéder au règlement selon les modalités prévues.</p>';
    }
}
