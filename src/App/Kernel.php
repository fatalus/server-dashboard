<?php

namespace App;

use Dashboard\Services\SystemService;
use Dashboard\Helpers\ConfigHelper;

class Kernel 
{
    public function handle(array $server): string
    {
        $config = ConfigHelper::getInstance();
        $app_config = $config->getAppConfig();

        $request_uri = $server['REQUEST_URI'];

        // Not the best solution ever (hard dependency on ConfigHelper), but better than having it hard in index.php
        if (isset($app_config['dev_env']) && $app_config['dev_env'] === true) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }

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