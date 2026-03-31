<?php

namespace Dashboard\Services;

use Dashboard\Services\ShellExecutorService;
use Dashboard\Services\StatusSaverService;
use Dashboard\Helpers\ConfigHelper;

class SystemService
{
    private ShellExecutorService $shellExecutor;
    private StatusSaverService $statusSaver;
    private ConfigHelper $config_helper;

    public function __construct()
    {
        $this->shellExecutor = new ShellExecutorService();
        $this->statusSaver = new StatusSaverService();
        $this->config_helper = ConfigHelper::getInstance();
    }

    public function getFullStatusReport(): array
    {
        $services = $this->config_helper->getConfiguredServices();
        $report = [];

        foreach ($services as $service) {
            $status = $this->getServiceStatus($service['name']);
            $this->statusSaver->saveStatus($service['name'], $status);
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