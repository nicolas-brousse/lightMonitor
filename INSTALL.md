# LightMonitor

## Dependencies

apt-get install php5-curl php-snmp php5-ssh2 php5-rrdtool


## Installation

- In Apache Vhost, add : Alias /app_path /app_path/public

- Duplicate /app/configs/*.yml-dist to /app/configs/*.yml
- Duplicate /public/.htaccess-dist to /public/.htaccess, and precise RewriteBase

- chmod 777 /data/log/*

- It's work !