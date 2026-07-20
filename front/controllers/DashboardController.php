<?php
class DashboardController extends Controller
{
    private DevisModel $devisModel;
    private FactureModel $factureModel;

    public function __construct()
    {
        $this->devisModel = new DevisModel();
        $this->factureModel = new FactureModel();
    }

    public function index()
    {
        $authMiddleware = new AuthMiddleware();
        if (!$authMiddleware->handle()) {
            return;
        }

        $clientId = (int) ($_SESSION['client']['id_client'] ?? 0);
        $devisList = $clientId > 0 ? $this->devisModel->findByClientId($clientId) : [];
        $factures = $clientId > 0 ? $this->factureModel->findByClientId($clientId) : [];

        $quotePendingStatuses = ['en_attente', 'en_attente_validation'];
        $quoteValidatedStatuses = ['valide_client'];
        $invoicePaidStatuses = ['payee'];

        $devisPending = 0;
        $devisValidated = 0;
        $devisTotalAmount = 0.0;
        $lastQuoteCreatedAt = null;

        foreach ($devisList as $devis) {
            $status = strtolower((string) ($devis['statut'] ?? ''));
            $devisTotalAmount += (float) ($devis['montant_total'] ?? 0);

            if (in_array($status, $quotePendingStatuses, true)) {
                $devisPending++;
            }

            if (in_array($status, $quoteValidatedStatuses, true)) {
                $devisValidated++;
            }

            if ($lastQuoteCreatedAt === null && !empty($devis['created_at'])) {
                $lastQuoteCreatedAt = (string) $devis['created_at'];
            }
        }

        $facturesPaid = 0;
        $facturesTotalAmount = 0.0;
        $lastInvoiceCreatedAt = null;

        foreach ($factures as $facture) {
            $status = strtolower((string) ($facture['statut'] ?? ''));
            $facturesTotalAmount += (float) ($facture['montant_ttc'] ?? 0);

            if (in_array($status, $invoicePaidStatuses, true)) {
                $facturesPaid++;
            }

            if ($lastInvoiceCreatedAt === null && !empty($facture['created_at'])) {
                $lastInvoiceCreatedAt = (string) $facture['created_at'];
            }
        }

        $activityStats = [
            'devis_total' => count($devisList),
            'devis_pending' => $devisPending,
            'devis_validated' => $devisValidated,
            'devis_total_amount' => $devisTotalAmount,
            'factures_total' => count($factures),
            'factures_paid' => $facturesPaid,
            'factures_total_amount' => $facturesTotalAmount,
            'last_quote_created_at' => $lastQuoteCreatedAt,
            'last_invoice_created_at' => $lastInvoiceCreatedAt,
        ];

        $this->render('dashboard/index', [
            'activityStats' => $activityStats,
        ]);
    }
}
