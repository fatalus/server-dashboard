<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dashboard\Services\SystemService;

$service = new SystemService();
$status = $service->getFullStatusReport();

?>
<style>
    body {
        font-family: sans-serif;
        font-size: 16px;
        margin: 50px;
    }
    thead > tr > th {
        padding-bottom: 0.8rem;
    }
    tbody > tr > td {
        padding: 3px 5px;
    }
    .status-card {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .status-card:hover {
        background-color: #f9f9f9;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
</style>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Server Dashboard</title>
    </head>
    <body>
        <div>
            <h2>Willkommen auf dem Server Dashboard</h2>
            <p>Hier kann man Informationen über den Server einsehen. Dies wird bald mit einem htaccess geschützt.</p>
            <p>Dies wird auf mehrere Dinge, wie z.B. Graphen (Chart.js) erweitert um das System ein wenig monitoren zu können. Im Moment sind es halt leider nur Karten die ein Paar Infos haben. Für Graphen o.ä. brauchen wir dann eine Datenbank.</p>
        </div>
        <div style="margin-top: 60px;">
            <h3>System Status</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <?php foreach ($status as $service_name => $service_status): 
                    $active = $service_status['process']['active'] ? true : ($service_status['process']['status'] ? true : false);
                    ?>
                    <div class="status-card">
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-align: left;"><span class="status-indicator" style="background-color: <?php echo $active ? 'green' : 'red'; ?>;"></span><?php echo $service_name; ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Status:</td>
                                    <td><?php echo $service_status['process']['status'] ? 'Running' : 'Stopped'; ?></td>
                                </tr>
                                <tr>
                                    <td>Enabled:</td>
                                    <td><?php echo $service_status['process']['enabled'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <td>Preset:</td>
                                    <td><?php echo $service_status['process']['preset']; ?></td>
                                </tr>
                            
                                <?php if ($service_status['status']['active_processes'] !== null): ?>
                                    <tr>
                                        <td>Active Processes:</td>
                                        <td><?php echo $service_status['status']['active_processes']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Idle Processes:</td>
                                        <td><?php echo $service_status['status']['idle_processes']; ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
</html>