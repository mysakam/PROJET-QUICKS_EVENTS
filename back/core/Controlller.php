<?php

abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewPath = dirname(__DIR__) . '/views/' . $view . '.php';
        $layoutPath = dirname(__DIR__) . '/views/layouts/' . $layout . '.php';

        if (!file_exists($viewPath) || !file_exists($layoutPath)) {
            http_response_code(500);
            echo 'Vue ou layout introuvable.';
            return;
        }

        extract($data, EXTR_SKIP);
        require $layoutPath;
    }
}
