<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dashboard\Services\SystemService;

$service = new SystemService();
$status = $service->getFullStatusReport();

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/index.css">
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
                    $is_active = $service_status['process']['active'];
                    ?>
                    <div class="status-card">
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-align: left;"><span class="status-indicator" style="background-color: <?php echo $is_active ? 'green' : 'red'; ?>;"></span><?php echo $service_name; ?></th>
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
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
</html>