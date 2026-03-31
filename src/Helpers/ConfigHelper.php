<?php

namespace Dashboard\Helpers;

class ConfigHelper
{
    private static $config_file = __DIR__ . '/../../data/config.json';
    private static $instance = null;
    private static $config = null;

    private function __construct() {
        $this->config = $this->loadConfig();
    }

    private function loadConfig(): array
    {
        if (!file_exists(self::$config_file)) {
            throw new \RuntimeException("Config file not found: " . self::$config_file);
        }

        $config = json_decode(file_get_contents(self::$config_file), true);

        if ($config === null) {
            throw new \RuntimeException("Failed to parse config file: " . self::$config_file);
        }

        return $config;
    }

    public static function getInstance(): self
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }

    public function getAppConfig(): array
    {
        return $this->config['app'] ?? [];
    }

    public function getConfiguredServices(): array
    {
        return $this->config['services'] ?? [];
    }
}