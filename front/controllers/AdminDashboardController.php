<?php

class AdminDashboardController extends AdminBaseController
{
    private NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('admin/dashboard', [
            'pageTitle' => 'Dashboard Admin',
            'lang' => $this->getLang(),
            'notifications' => $this->notificationModel->findRecentForAdmin(6),
            'unreadNotificationsCount' => $this->notificationModel->countUnreadForAdmin(),
        ]);
    }

    public function markNotificationAsRead(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->notificationModel->markAsRead($id);
        redirect(route('admin_dashboard'));
    }

    public function openNotification(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $notification = $this->notificationModel->findByIdForAdmin($id);
        if (!$notification) {
            redirect(route('admin_dashboard'));
            return;
        }

        $this->notificationModel->markAsRead($id);

        $targetUrl = (string) ($notification['payload']['target_url'] ?? '');
        if ($targetUrl === '') {
            redirect(route('admin_dashboard'));
            return;
        }

        redirect($targetUrl);
    }

    public function deleteNotification(int $id): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->notificationModel->deleteForAdmin($id);
        redirect(route('admin_dashboard'));
    }
}
