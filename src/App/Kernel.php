<?php

namespace App;

use Dashboard\Services\SystemService;

class Kernel 
{
    public function handle(array $server): string
    {
        $request_uri = $server['REQUEST_URI'];

        return $this->router($request_uri);
    }

    private function router(string $route): string
    {
        $service = new SystemService();

        return match($route) {
            "/", "/dashboard" => $this->render('dashboard.php', [
                'status' => $service->getFullStatusReport()
            ]),
            default => $this->render("404.php", [])
        };
    }

    private function render(string $template, array $data = []): string
    {
        $template_path =  __DIR__ . '/../../templates/' . $template;

        if (!file_exists($template_path) || !is_readable($template_path)) {
            return "";
        }

        extract($data);

        ob_start();
        require $template_path;
        return ob_get_clean();
    }
}