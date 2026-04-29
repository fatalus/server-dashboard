<?php

namespace App;

use Dashboard\Services\SystemService;

class Kernel 
{
    public function handle(array $server): string
    {
        // TODO: Create a Router that handles request more MVC-ish. But thats not really neccessary as this is more or less a single page application.
        $service = new SystemService();
        $status = $service->getFullStatusReport();

        return $this->render('dashboard.php', [
            'status' => $status
        ]);
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