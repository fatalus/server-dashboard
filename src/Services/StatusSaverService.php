<?php

namespace Dashboard\Services;

class StatusSaverService
{
    private static $data_dir = __DIR__ . '/../../data/';

    public function saveStatus(string $service_name, array $status): void
    {
        $filepath = self::$data_dir . $service_name . '_status.json';

        if (!is_dir(self::$data_dir)) {
            mkdir(self::$data_dir, 0775, true);
        }

        if (!file_exists($filepath)) {
            dump($filepath. " does not exist, creating it...");
            if (!touch($filepath)) {
                throw new \RuntimeException("Failed to create file: $filepath");
            }
            file_put_contents($filepath, json_encode([], JSON_PRETTY_PRINT));
        }
        
        $current_status = json_decode(file_get_contents($filepath), true);

        $current_status[time()] = [
            'status' => $status,
        ];

        file_put_contents($filepath, json_encode($current_status, JSON_PRETTY_PRINT));
    }
}