<?php

namespace Dashboard\Services;

class SystemService
{
    public function getFullStatusReport(): array
    {
        return [
            'Nginx' => $this->getNginxStatus(),
            'PHP' => $this->getPhpStatus(),
            'SSH' => $this->getSshStatus(),
            'Firewall' => $this->getFirewallStatus(),
        ];
    }

    public function getNginxStatus(): array
    {
        $output = [];
        $retval = 0;
        exec("systemctl status --quiet nginx.service", $output, $retval);

        return $this->getSystemCtlData($output);
    }

    public function getPhpStatus(): array
    {
        $parts = explode('.', phpversion());
        $fpm_name = "php" . $parts[0] . "." . $parts[1] . "-fpm.service";

        $output = [];
        $retval = 0;
        exec("systemctl status --quiet $fpm_name", $output, $retval);

        return $this->getSystemCtlData($output);
    }

    public function getSshStatus(): array
    {
        $output = [];
        $retval = 0;
        exec("systemctl status --quiet ssh.service", $output, $retval);

        return $this->getSystemCtlData($output);
    }

    public function getFirewallStatus(): array
    {
        $output = [];
        $retval = 0;
        exec("systemctl status --quiet ufw.service", $output, $retval);

        return $this->getSystemCtlData($output);
    }

    private function getSystemCtlData(array $systemctl_output) {
        $result = [
            'process' => [
                'status' => null,
                'active' => null,
                'enabled' => false,
                'preset' => null,
            ],
            'status' => [
                'active_processes' => null,
                'idle_processes' => null,
                'traffic' => null,
            ]
        ];

        foreach ($systemctl_output as $line) {
            $line = trim($line);
            if (strpos($line, 'Active:') !== false) {
                if (preg_match('/\(([^)]*)\)/', $line, $match)) {
                    $result['process']['status'] = $match[1];
                } else if (preg_match('/Active:\s+(\w+)/', $line, $match)) {
                    $result['process']['active'] = $match[1];
                }
            } else if (strpos($line, 'Loaded:') !== false) {
                if (preg_match('/\(([^)]*)\)/', $line, $match)) {
                    $exploded = explode(';', $match[1]);
                    $result['process']['enabled'] = trim($exploded[1]) === 'enabled' ? true : false;
                    $result['process']['preset'] = trim(explode(':', $exploded[2])[1]);
                }
            } else if (strpos($line, 'Status:') !== false) {
                if (preg_match('/\"([^"]*)\"/', $line, $match)) {
                    $parts = explode(',', $match[1]);
                    $result['status']['active_processes'] = trim(str_replace('Processes active:', '', $parts[0]));
                    $result['status']['idle_processes'] = trim(str_replace('idle:', '', $parts[1]));
                    $result['status']['traffic'] = trim(str_replace('Traffic:', '', $parts[4]));
                }
            }
        }

        return $result;
    }
}