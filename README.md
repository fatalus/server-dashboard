# Server Dashboard
A simple tool to monitor your server and its running services via a Web-Interface built with raw PHP without any Frameworks or libraries.
Currently only working with Systemd services.

<hr>

### Requirements
- composer
- Web-Server (Nginx, Apache2, etc.) with a php runtime like php-fpm pointing to `./public/index.php`

#### Setup
On a server you can simply clone the project, configure your web server and then simply run and you're done!
```bash
composer setup
```
For development purposes simply run this and then create a simple webserver using php -S
```bash
composer dev
php -S localhost:800 -t public/
```
<br>

To configure the services that should be monitored simply create a JSON file in `./data/config.json`. This happens automatically if you've used any of the composer scripts listed above. Here you can monitor as many services as you want to.

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
- [x] Add detection for development vs. Production mode and activate error display
- [ ] Add support for System Utilities like RAM, CPU and Disk space usage
- [ ] Display data in Graphs (Chart.js)
- [ ] Add support for different (runit, etc.)

```php
// Debugging:
error_reporting(E_ALL);
ini_set('display_errors', 1);
```