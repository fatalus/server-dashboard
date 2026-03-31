<?php

namespace Dashboard\Services;

class ShellExecutorService
{
    private readonly string $init_system;
    private readonly array $init_system_commands;

    public function __construct()
    {
        $this->init_system = $this->detectInitSystem();
        $this->init_system_commands = [
            'systemd' => "systemctl show %s --property=ActiveState,SubState,UnitFileState,UnitFilePreset",
            'runit' => "sv status %s",
        ];
    }

    /**
     * Detects the init system and default to systemd.
     */
    private function detectInitSystem(): string
    {
        $comm_fp = '/proc/1/comm';
        if (file_exists($comm_fp) && is_readable($comm_fp)) {
            $init_process = trim(file_get_contents($comm_fp));
            return $init_process;
        }

        return 'systemd';
    }

    /**
     * Gets the status of a service using the appropriate command based on the detected init system.
     */
    public function getServiceStatus(string $service_name): array
    {
        if (!isset($this->init_system_commands[$this->init_system])) {
            throw new \Exception("Unsupported init system: " . $this->init_system);
        }

        $command = sprintf($this->init_system_commands[$this->init_system], $service_name);
        $output = [];
        $retval = 0;
        exec($command, $output, $retval);

        return [
            'output' => $output,
            'status' => $retval === 0,
        ];
    }
}