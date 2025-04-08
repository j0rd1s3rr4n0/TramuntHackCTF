#!/usr/bin/bash -e
alias publicip='curl -s ifconfig.me | grep -oE "((1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])\.){3}(1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])"';
alias myip="ip addr | grep -oE '((1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])\.){3}(1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])'";
alias restartall="service apache2 restart && service mariadb restart && service ssh restart";

# Step 1: Update package list and install Apache
echo "Updating package list..."
echo "Installing Apache..."
# Step 2: Install MariaDB
echo "Installing MariaDB..."

echo "$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )\n$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )\n" | apt install mariadb-server -y


# Secure the MariaDB installation
echo "Securing MariaDB..."
echo "mysql_secure_installation"
service mariadb start
cat -A /var/www/credentials.cfg
sed -i 's/\r//g' /var/www/credentials.cfg
echo -e "$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )\n$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2;)\n\n\nn\n\n\n" > anwsers.txt
cat answers.txt
mysql_secure_installation <<EOF

y
$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )
$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )
y
y
y
y
EOF

rm answers.txt

# Step 3: Install PHP and necessary modules
echo "Installing PHP and modules..."
apt install php libapache2-mod-php php-mysql -y

# Step 4: Configure Apache to prefer PHP over HTML
echo "Configuring Apache to use PHP files as default..."
sed -i 's/DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm/DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm/' /etc/apache2/mods-enabled/dir.conf

# Reload Apache to apply changes
echo "Reloading Apache..."
service apache2 reload

# Step 5: Set up a Virtual Host for your domain
echo "Creating a Virtual Host for your website..."
# Replace '$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )' with your actual domain
mkdir /var/www/$(awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)
chown -R $(awk 'NR==9' /var/www/credentials.cfg | cut -d'=' -f2):$( awk 'NR==10' /var/www/credentials.cfg | cut -d'=' -f2; ) /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )

# Create virtual host configuration file
bash -c "cat > /etc/apache2/sites-available/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; ).conf <<EOF
<VirtualHost *:$( awk 'NR==11' /var/www/credentials.cfg | cut -d'=' -f2; )>
    ServerName Localhost
    ServerAlias www.$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )
    ErrorLog /var/log/apache2/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )/error.log
    CustomLog /var/log/apache2/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )/access.log combined
</VirtualHost>
EOF"



echo "display_errors = On\ndisplay_startup_errors = On\nerror_reporting = E_ALL\n" >> /etc/php/8.3/apache2/php.ini
# Enable the new site and disable the default site
echo "Enabling the Virtual Host \"$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )\" ... "
a2ensite $( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; ).conf
a2dissite 000-default.conf

# Test Apache configuration and reload
echo "Testing Apache configuration..."
service apache2 reload
echo "Reloading Apache..."
service apache2 restart

# Step 6: Create a PHP info file to test PHP processing
echo "Creating a test PHP file..."
echo "<?php phpinfo(); ?>" | tee /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )/info.php > /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )/info.php

# Step 7: Test PHP Processing
echo "Testing PHP processing..."
echo "Visit http://$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )/info.php in your browser to verify PHP installation"

# Optional: Step 8: Test database connection from PHP
echo "Creating a test database..."
mariadb -e "CREATE DATABASE $( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2; );"
mariadb -e "CREATE USER '$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2; )'@'%' IDENTIFIED BY '$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )';"
mariadb -e "GRANT ALL ON $( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2; ).* TO '$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2; )'@'%';"
mariadb -e "FLUSH PRIVILEGES;"

# Clean up
echo "Cleaning up..."
# rm /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )/info.php


echo "ServerName localhost" >> /etc/apache2/apache2.conf
mkdir -p /var/log/apache2/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)/
chown -R www-data:www-data /var/log/apache2/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)/
chmod -R 750 /var/log/apache2/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)/
service apache2 restart


# Config .env file
sed -i "s/nombre_de_tu_base_de_datos/$( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)/../.env
sed -i "s/nombre_de_usuario_de_la_base_de_datos/$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)/../.env
sed -i "s/contraseña_de_la_base_de_datos/$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)/../.env


echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/nmap" >> /etc/sudoers

echo "Installation and Configuration completed!"
service apache2 restart &> /dev/null
service mariadb restart &> /dev/null

mariadb < /var/www/hackermanland.sql

echo "Habilitando autoarranque de servicios..."
update-rc.d apache2 defaults
update-rc.d mariadb defaults

rm /var/www/hackermanland.sql
rm /var/www/credentials.cfg
rm /var/www/EasySetup.sh