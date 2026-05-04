<?php

namespace Dashboard\Helpers;

use Dashboard\Contracts\HelperInterface;

class ConfigHelper implements HelperInterface
{
    private static string $config_file = __DIR__ . '/../../data/config.json';
    private static ?object $instance = null;
    private static ?array $config = null;

    private function __construct() {
        self::$config = self::loadConfig();
    }

    private static function loadConfig(): array
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
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getAppConfig(): array
    {
        return self::$config['app'] ?? [];
    }

    public function getConfiguredServices(): array
    {
        return self::$config['services'] ?? [];
    }
}