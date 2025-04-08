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


echo "Creating a test database..."
mariadb -e "CREATE DATABASE $( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2; );"
mariadb -e "CREATE USER '$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2; )'@'%' IDENTIFIED BY '$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )';"
mariadb -e "GRANT ALL ON $( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2; ).* TO '$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2; )'@'%';"
mariadb -e "FLUSH PRIVILEGES;"

# Clean up
echo "Cleaning up..."



echo "ServerName localhost" >> /etc/apache2/apache2.conf
mkdir -p /var/log/apache2/management.ctf.com/
# chown -R www-data:www-data /var/log/apache2/management.ctf.com/
chmod -R 750 /var/log/apache2/management.ctf.com/
service apache2 restart


# Config .env file
sed -i "s/nombre_de_tu_base_de_datos/$( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/management.ctf.com/../.env
sed -i "s/nombre_de_usuario_de_la_base_de_datos/$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/management.ctf.com/../.env
sed -i "s/contraseña_de_la_base_de_datos/$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/management.ctf.com/../.env

echo "Installation and Configuration completed!"

service apache2 restart &> /dev/null
service mariadb restart &> /dev/null

mariadb < /var/www/crear_tablas.sql


# Archivo de configuración de Apache (puede variar dependiendo de la distribución)
CONFIG_FILE="/etc/apache2/apache2.conf"  # Para distribuciones basadas en Debian/Ubuntu
# CONFIG_FILE="/etc/httpd/conf/httpd.conf"  # Para distribuciones basadas en RedHat/CentOS

# Verificar si el archivo de configuración existe
if [ ! -f "$CONFIG_FILE" ]; then
  echo "El archivo de configuración de Apache no se encuentra en $CONFIG_FILE."
  exit 1
fi

# Realizar una copia de seguridad del archivo de configuración
cp "$CONFIG_FILE" "${CONFIG_FILE}.bak"
echo "Se ha creado una copia de seguridad en ${CONFIG_FILE}.bak"



# Verificar si el script se está ejecutando como root
if [ "$(id -u)" -ne 0 ]; then
  echo "Este script debe ejecutarse como root."
  exit 1
fi

# Nombre del nuevo usuario
USER_NAME="j0rd1s3rr4n0"

# Crear el nuevo usuario
useradd -m -s /bin/bash "$USER_NAME"

# Establecer una contraseña para el nuevo usuario
echo "$USER_NAME:password" | chpasswd

# Agregar el usuario al grupo sudo (Ubuntu/Debian)
usermod -aG sudo "$USER_NAME"

# Añadir el usuario al gripo root (Debian/Ubuntu)
usermod -aG root "$USER_NAME"

# Agregar el usuario al grupo wheel (CentOS/Red Hat)
# usermod -aG wheel "$USER_NAME"  # Descomenta esta línea si usas CentOS o Red Hat

echo "CTF{df30cb178eb8e37728f39b3e6551c8de}" > /root/root.txt
# Confirmar que el usuario se ha creado correctamente


# Modificar las directivas User y Group en el archivo de configuración
sed -i 's/^User .*/User j0rd1s3rr4n0/' "$CONFIG_FILE"
sed -i 's/^Group .*/Group j0rd1s3rr4n0/' "$CONFIG_FILE"

chown -R j0rd1s3rr4n0:j0rd1s3rr4n0 /root

echo "The user.txt file was created in the hackerman's home directory." > /home/$USER_NAME/user.txt
echo 'export CFLAGS="$CFLAGS -DBIG_SECURITY_HOLE"' >> /etc/profile

# Reiniciar Apache para aplicar los cambios
service apache2 restart 

echo "Apache ahora se ejecuta como root."
echo "Habilitando autoarranque de servicios..."

rm /var/www/credentials.cfg
rm /var/www/crear_tablas.sql
rm /var/www/EasySetup.sh
rm /var/www/database/ -rf

update-rc.d apache2 defaults
update-rc.d mariadb defaults