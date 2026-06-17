<?php
// app/controllers/BaseController.php

abstract class BaseController {

    protected function view(string $viewPath, array $data = []): void {
        extract($data);
        $flash = getFlash();
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            die("View not found: $viewFile");
        }
        require $viewFile;
    }

    protected function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void {
        redirect($url);
    }

    protected function input(string $key, string $default = ''): string {
        return sanitize($_POST[$key] ?? $_GET[$key] ?? $default);
    }

    protected function inputRaw(string $key, string $default = ''): string {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function param(string $key, string $default = ''): string {
        return sanitize($_GET[$key] ?? $default);
    }
}
