<?php

class AdminDashboardController extends AdminBaseController
{
    public function index(): void
    {
        if (!$this->ensureAdmin()) {
            return;
        }

        $this->render('admin/dashboard', [
            'pageTitle' => 'Dashboard Admin',
            'lang' => $this->getLang(),
        ]);
    }
}
