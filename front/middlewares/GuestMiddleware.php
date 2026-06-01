<?php

class GuestMiddleware
{
    public function handle(): bool
    {
        if (!empty($_SESSION['client'])) {
            redirect(route('catalogues'));
            return false;
        }

        return true;
    }
}
