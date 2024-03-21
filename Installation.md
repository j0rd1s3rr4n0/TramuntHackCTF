USER:PASSWORD
sysadmin:P@ssw0rd!

```bash
#!/bin/bash
sudo su

curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc   | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && echo "deb https://ngrok-agent.s3.amazonaws.com buster main"   | sudo tee /etc/apt/sources.list.d/ngrok.list && sudo apt update && sudo apt install ngrok

# Instalar Git
apt install git -y

# Instalar Apache2
sudo apt install apache2 -y

# Instalar MySQL Server
sudo apt install mysql-server -y

# Configurar MySQL Server
sudo mysql_secure_installation <<EOF

Y
tu_contraseña_de_root_mysql
tu_contraseña_de_root_mysql
Y
Y
Y
Y
EOF

# Instalar PHP y módulos necesarios
sudo apt install php libapache2-mod-php php-mysql -y



# Crear archivo de configuración del sitio web
sitio_web_conf="/etc/apache2/sites-available/ctf.hackmeifyoucan.conf"
sudo touch $sitio_web_conf
sudo tee $sitio_web_conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerAdmin admin@ctf.hackmeifyoucan.com
    ServerName ctf.hackmeifyoucan.com
    ServerAlias ctf.hackmeifyoucan.com
    DocumentRoot /var/www/html/ctf.hackmeifyoucan.com/public_html

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    <Directory /var/www/html/ctf.hackmeifyoucan.com/public_html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF


# Crear archivo de configuración del sitio web
sitio_web_conf="/etc/apache2/sites-available/hackmeifyoucan.conf"
sudo touch $sitio_web_conf
sudo tee $sitio_web_conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerAdmin admin@hackmeifyoucan.com
    ServerName hackmeifyoucan.com
    ServerAlias www.hackmeifyoucan.com
    DocumentRoot /var/www/html/hackmeifyoucan.com/public_html

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    <Directory /var/www/html/hackmeifyoucan.com/public_html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

# Habilitar el sitio web
sudo a2ensite ctf.hackmeifyoucan.conf
sudo a2ensite hackmeifyoucan.conf

# Reiniciar Apache para que los cambios surtan efecto
sudo systemctl reload apache2

cd /var/www/
git clone https://github.com/j0rd1s3rr4n0/VulnWeb.git
mv VulnWeb/* html/
rm VulnWeb/ -r
cd /var/www/html/hackmeifyoucan.com

NUEVA_BASE_DE_DATOS="HOLA"
NUEVO_USUARIO="HOLA"
NUEVA_CONTRASEÑA="HOLA"
envpath="/var/www/html/hackmeifyoucan.com/.env"

# Realizar cambios en las variables de la base de datos
sed -i "s/DB_DATABASE=nombre_de_tu_base_de_datos/DB_DATABASE=$NUEVA_BASE_DE_DATOS/g" $envpath
sed -i "s/DB_USERNAME=nombre_de_usuario_de_la_base_de_datos/DB_USERNAME=$NUEVO_USUARIO/g"  $envpath
sed -i "s/DB_PASSWORD=contraseña_de_la_base_de_datos/DB_PASSWORD=$NUEVA_CONTRASEÑA/g" $envpath



```