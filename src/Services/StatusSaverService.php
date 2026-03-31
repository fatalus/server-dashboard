<?php

namespace Dashboard\Services;

use Dashboard\Helpers\ConfigHelper;

class StatusSaverService
{
    private static $data_dir = __DIR__ . '/../../data/';
    private ConfigHelper $config_helper;

    public function __construct()
    {
        $this->config_helper = ConfigHelper::getInstance();
    }

    public function saveStatus(string $service_name, array $status): void
    {
        $filepath = self::$data_dir . $service_name . '_status.json';

        if (!is_dir(self::$data_dir)) {
            mkdir(self::$data_dir, 0775, true);
        }

        if (!file_exists($filepath)) {
            if (!touch($filepath)) {
                throw new \RuntimeException("Failed to create file: $filepath");
            }
            file_put_contents($filepath, json_encode([], JSON_PRETTY_PRINT));
        }
        
        $current_status = json_decode(file_get_contents($filepath), true);

        $last_timestamp = array_key_last($current_status);
        if ($last_timestamp !== null && time() - $last_timestamp < $this->config_helper->getAppConfig()['status_save_interval'] * 60) {
            $current_status[time()] = [
                'status' => $status,
            ];
    
            file_put_contents($filepath, json_encode($current_status, JSON_PRETTY_PRINT));
        }
    }
}