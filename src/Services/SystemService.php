<?php

namespace Dashboard\Services;

use Dashboard\Services\ShellExecutorService;

class SystemService
{
    private ShellExecutorService $shellExecutor;
    private static $service_file = __DIR__ . '/../../data/config.json';

    public function __construct()
    {
        $this->shellExecutor = new ShellExecutorService();
    }

    public function getFullStatusReport(): array
    {
        $services = json_decode(file_get_contents(self::$service_file), true)['services'];
        $report = [];

        foreach ($services as $service) {
            $status = $this->getServiceStatus($service['name']);
            $report[$service['display_name']] = $status;
        }

        return $report;
    }

    public function getServiceStatus(string $service_name): array
    {
        $result = $this->shellExecutor->getServiceStatus($service_name);

        if ($result['status']) {
            return $this->parseSystemctlShow($result['output']);
        }

        return [
            'process' => [
                'active' => false,
                'status' => null,
                'enabled' => false,
                'preset' => null,
            ]
        ];
    }

    // TODO: move to ShellExecutor Service and make it more generic for other init systems
    private function parseSystemctlShow(array $lines): array
    {
        $result = [
            'process' => [
                'active' => null,
                'status' => null,
                'enabled' => false,
                'preset' => null,
            ]
        ];

        foreach ($lines as $line) {
            [$key, $value] = array_pad(explode('=', trim($line), 2), 2, null);

            switch ($key) {
                case 'ActiveState':
                    $result['process']['active'] = $value === 'active';
                    break;

                case 'SubState':
                    $result['process']['status'] = $value;
                    break;

                case 'UnitFileState':
                    $result['process']['enabled'] = ($value === 'enabled');
                    break;

                case 'UnitFilePreset':
                    $result['process']['preset'] = $value;
                    break;
            }
        }

        return $result;
    }
}