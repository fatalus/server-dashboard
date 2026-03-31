# Server Dashboard

Create a JSON file with all Services that should be monitored like this
```json
{
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