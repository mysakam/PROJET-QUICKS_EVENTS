<?php

class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        $layoutPath = __DIR__ . '/../views/layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo 'Vue introuvable : ' . $view;
            return;
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if (!file_exists($layoutPath)) {
            echo $content;
            return;
        }

        require $layoutPath;
    }
}