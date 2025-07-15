# https://wiki.archlinux.org/title/MariaDB
# https://gist.github.com/superjojo140/18e250786d977b27571124f81bba5018
# https://wiki.archlinux.org/title/Apache_HTTP_Server
# https://wiki.archlinux.org/title/Wordpress
# https://wiki.archlinux.org/title/PhpMyAdmin
# https://wiki.archlinux.org/title/Nginx

# ================================================
# ✅ 1️⃣  Install Apache + PHP + MariaDB (LAMP)
# ------------------------------------------------

# Example (adjust for your packages):
# sudo pacman -S apache mariadb php php-apache php-gd php-intl php-mysql php-pgsql php-sqlite php-curl php-zip php-mbstring php-imagick phpmyadmin

# ================================================
# ✅ 2️⃣  Enable & start Apache
# ------------------------------------------------

sudo systemctl enable httpd
sudo systemctl start httpd

# ================================================
# ✅ 3️⃣  Edit Apache config: httpd.conf
# ------------------------------------------------

sudo nano /etc/httpd/conf/httpd.conf

# Make sure these modules are uncommented:
LoadModule mpm_event_module modules/mod_mpm_event.so
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule unique_id_module modules/mod_unique_id.so

# Add this or make /etc/httpd/conf/extra/phpmyadmin.conf
Alias /phpmyadmin "/usr/share/webapps/phpMyAdmin"
<Directory "/usr/share/webapps/phpMyAdmin">
 DirectoryIndex index.php
 AllowOverride All
 Options FollowSymlinks
 Require all granted
</Directory>

# Add PHP handler at the bottom (if not present):
IncludeOptional conf/conf.d/*.conf
LoadModule mpm_prefork_module modules/mod_mpm_prefork.so
LoadModule php_module modules/libphp.so
AddHandler php-script .php
Include conf/extra/php_module.conf
Include conf/extra/phpmyadmin.conf

# Optional: Uncomment Include vhosts
Include conf/extra/httpd-vhosts.conf

# Initialize MariaDB
sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql

sudo systemctl enable mariadb
sudo systemctl start mariadb

# Run security setup
# Follow prompts — set strong password, remove anonymous users, disallow root remote login, remove test DB.
sudo mysql_secure_installation

# uncomment vhosts impact solution
sudo mkdir -p /var/log/httpd
sudo chown http:http /var/log/httpd

# ================================================
# ✅ 4️⃣  Setup Virtual Host
# ------------------------------------------------

sudo nano /etc/httpd/conf/extra/httpd-vhosts.conf

# Example vhost:
# ----------------------------------------
# 🏠 Default VHost (localhost -> /srv/http)
# ----------------------------------------
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "/srv/http"

    <Directory "/srv/http">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "/var/log/httpd/localhost-error_log"
    CustomLog "/var/log/httpd/localhost-access_log" common
</VirtualHost>

# ----------------------------------------
# 🌐 Example VHost (mytest.local -> /srv/http/mytest)
# ----------------------------------------

<VirtualHost *:80>
    ServerName local.learnwordpress.com
    DocumentRoot "/home/exploiter/Web/learnwordpress"

    <Directory "/home/exploiter/Web/learnwordpress">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "/var/log/httpd/learnwordpress.local-error_log"
    CustomLog "/var/log/httpd/learnwordpress.local-access_log" common
</VirtualHost>

# ================================================
# ✅ 5️⃣  Add to /etc/hosts
# ------------------------------------------------

sudo nano /etc/hosts

# Add:
127.0.0.1   local.learnwordpress.com

# ================================================
# ✅ 6️⃣  Fix PHP session & upload limits
# ------------------------------------------------

sudo nano /etc/php/php.ini

# Uncomment extenstion
extension=bz2 or bz2.so
extension=gd
extension=mysqli or mysqli.so
extension=pdo_mysql
# Recommended edits:
session.save_path = "/tmp"            # or valid writable folder
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 256M
max_execution_time = 300

# ================================================
# ✅ 7️⃣  phpMyAdmin: fix session cookie error
# ------------------------------------------------

sudo nano /etc/webapps/phpmyadmin/config.inc.php

$cfg['ForceSSL'] = false; // Or true if you have SSL
$cfg['Servers'][$i]['AllowNoPassword'] = true; // Not related to cookie but useful sometimes

# cookie encryption
# find this
$cfg['blowfish_secret'] = '';

# Generate randome string
openssl rand -hex 16
$cfg['blowfish_secret'] = 'put_that_string'; 

# Solution for: $cfg['TempDir'] is not accessible
sudo mkdir -p /usr/share/webapps/phpMyAdmin/tmp
sudo chown -R http:http /usr/share/webapps/phpMyAdmin/tmp
sudo chmod 700 /usr/share/webapps/phpMyAdmin/tmp

# Solution for: configuration storage is not completely configured
sudo mysql -u root -p

CREATE DATABASE phpmyadmin;
GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'pma'@'localhost' IDENTIFIED BY 'strongpassword';
FLUSH PRIVILEGES;
EXIT;

# Load the sructure
mysql -u root -p phpmyadmin < /usr/share/webapps/phpMyAdmin/sql/create_tables.sql

# open config.inc.php
# Add or uncomment these lines:
$cfg['Servers'][$i]['controlhost'] = 'localhost';
$cfg['Servers'][$i]['controluser'] = 'pma';
$cfg['Servers'][$i]['controlpass'] = 'your_pma_password';
$cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
$cfg['Servers'][$i]['bookmarktable'] = 'pma__bookmark';
$cfg['Servers'][$i]['relation'] = 'pma__relation';
$cfg['Servers'][$i]['table_info'] = 'pma__table_info';
$cfg['Servers'][$i]['table_coords'] = 'pma__table_coords';
$cfg['Servers'][$i]['pdf_pages'] = 'pma__pdf_pages';
$cfg['Servers'][$i]['column_info'] = 'pma__column_info';
$cfg['Servers'][$i]['history'] = 'pma__history';
$cfg['Servers'][$i]['table_uiprefs'] = 'pma__table_uiprefs';
$cfg['Servers'][$i]['tracking'] = 'pma__tracking';
$cfg['Servers'][$i]['userconfig'] = 'pma__userconfig';
$cfg['Servers'][$i]['recent'] = 'pma__recent';
$cfg['Servers'][$i]['favorite'] = 'pma__favorite';
$cfg['Servers'][$i]['users'] = 'pma__users';
$cfg['Servers'][$i]['usergroups'] = 'pma__usergroups';
$cfg['Servers'][$i]['navigationhiding'] = 'pma__navigationhiding';
$cfg['Servers'][$i]['savedsearches'] = 'pma__savedsearches';
$cfg['Servers'][$i]['central_columns'] = 'pma__central_columns';
$cfg['Servers'][$i]['designer_settings'] = 'pma__designer_settings';
$cfg['Servers'][$i]['export_templates'] = 'pma__export_templates';

# for custom port just change
$cfg['Servers'][$i]['controlport'] = '3307';

# confirm mariadb port
sudo ss -tlnp | grep mariadb

# Default 
$cfg['Servers'][$i]['auth_type'] = 'cookie';

# change to avoid entering user/pass in phpmyadmin
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = 'your_root_password';


# ================================================
# ✅ 7️⃣  php: opache and config
# ------------------------------------------------

sudo nano /etc/php/php.ini

opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=0
opcache.validate_timestamps=1

# Other option
# Open your php.ini
# Make sure that folder exists and is writable by the PHP user (www-data for Apache on Ubuntu, or http for Arch Linux).
session.save_path = "/var/lib/php/sessions" // Example Path

# Optional above will do the trick
# Make sure session.save_path folder exists and writable:
sudo mkdir -p /tmp
sudo chmod 1777 /tmp

# ================================================
# ✅ 8️⃣  Prepare WordPress project folders
# ------------------------------------------------

cd /home/exploiter/Web/learnwordpress

# Create required folders:
mkdir -p wp-content/upgrade
mkdir -p wp-content/cache
mkdir -p wp-content/breeze-config

# ================================================
# ✅ 9️⃣  Fix ownership & permissions
# ------------------------------------------------

# Inside your-project dir
# Arch Apache runs as 'http'
sudo chown -R http:http .

# Folders 755, Files 644
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# check your groups
groups exploiter
# add http to groups
sudo usermod -a -G http exploiter
# refresh
newgrp http

# Optional above will do the trick
# Make .htaccess if missing
touch .htaccess
sudo chown http:http .htaccess
chmod 664 .htaccess

# ================================================
# ✅ 🔑 10️⃣  wp-config.php recommended settings
# ------------------------------------------------

# Add near the bottom of wp-config.php:
define('FS_METHOD', 'direct');  // Skip FTP prompt, use direct file writes.

# Optional
# Method WordPress should use for file system writes
define('FS_METHOD', 'ftpext'); // or 'ftpsockets', or 'ftps', or 'direct'

# Your FTP hostname
define('FTP_HOST', 'ftp.example.com');

# FTP Username
define('FTP_USER', 'your_ftp_username');

# FTP Password
define('FTP_PASS', 'your_ftp_password');

# FTP Port (optional, default 21)
define('FTP_PORT', 21);

# Use SSL for FTP (FTPS)
define('FTP_SSL', true); // true for FTPS, false for plain FTP

# ================================================
# ✅ 11️⃣  Recommended .htaccess content
# ------------------------------------------------

# File: .htaccess
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>

# ================================================
# ✅ 12️⃣  Restart Apache to apply all
# ------------------------------------------------

sudo systemctl restart httpd

# ================================================
# ✅ 13️⃣  Breeze plugin: fix config/cache issues
# ------------------------------------------------

# In WP Admin → Breeze → Settings → Save Changes
# This regenerates:
#   wp-content/advanced-cache.php
#   wp-content/breeze-config/breeze-config.php

# Make sure:
#   wp-content/, breeze-config/, advanced-cache.php, .htaccess
# are owned by 'http' and writable.

# ================================================
# ✅ 14️⃣  Test Checklist
# ------------------------------------------------

# [x] Local domain loads: http://local.learnwordpress.com
# [x] Can install/update plugins & themes (no FTP prompt)
# [x] Breeze works & cache clears
# [x] phpMyAdmin login works, no session cookie error
# [x] File uploads work, large files allowed if limits adjusted

# ================================================
# ✅ 15️⃣  Common troubleshoot
# ------------------------------------------------

# Check Apache user:
ps aux | grep httpd

# Check PHP error log:
sudo journalctl -u httpd

# Check file permissions:
ls -la

# ================================================
# 🎉 DONE — Full local dev config, clean & tested.
# ------------------------------------------------
