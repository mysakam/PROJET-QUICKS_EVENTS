<?php
class Controller{
    protected function view(string $file): void
{require __DIR__ . '/../views/' .$file . '.php';
}
}