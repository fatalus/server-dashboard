# Server Dashboard
A simple tool to monitor your server and its running services via a Web-Interface built with raw PHP without any Frameworks or libraries.
Currently only working with Systemd services.

### Requirements
- composer
- Web-Server (Nginx, Apache2, etc.) with a php runtime like php-fpm pointing to `./public/index.php`

Create a JSON file with all Services that should be monitored like this in `./data/config.json`
```json
{
    "app" : {
        "saving_interval_mins": 15
    },
    "services": [
        {
            "name": "nginx.service",
            "display_name": "Nginx"
        },
        {
            "name": "php8.4-fpm.service",
            "display_name": "PHP"
        },
        {
            "name": "ssh.service",
            "display_name": "SSH"
        },
        {
            "name": "ufw.service",
            "display_name": "Firewall (UFW)"
        }
    ]
}
```
### Roadmap
[ ] Add detection for development vs. Production mode and activate error display
[ ] Add support for System Utilities like RAM, CPU and Disk space usage
[ ] Display data in Graphs (Chart.js)
[ ] Add support for different (runit, etc.)

```php
// Debugging:
error_reporting(E_ALL);
ini_set('display_errors', 1);
```