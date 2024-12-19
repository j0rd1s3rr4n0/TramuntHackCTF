#!/usr/bin/bash -e
alias publicip='curl -s ifconfig.me | grep -oE "((1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])\.){3}(1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])"';
alias myip="ip addr | grep -oE '((1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])\.){3}(1?[0-9][0-9]?|2[0-4][0-9]|25[0-5])'";
alias restartall="service apache2 restart && service mariadb restart && service ssh restart";

echo "Updating package list..."
echo "Installing Apache..."

apt update -y
apt install cron -y
apt install adduser -y

touch /root/.back_credentials

pass=$(shuf -n 1 /var/www/rockyou.txt)
# Create User
adduser pepe <<EOF
$pass
$pass
Full Name The Karten
208
34666666666
34966666666
This is the Karten
EOF
echo "pepe:$pass" >> /root/.back_credentials

pass=$(shuf -n 1 /var/www/rockyou.txt)
adduser admin <<EOF
$pass
$pass
Full Name The Karten
208
34666666666
34966666666
This is the Karten
EOF
echo "admin:$pass" >> /root/.back_credentials

pass=$(shuf -n 1 /var/www/rockyou.txt)
adduser armando <<EOF
$pass
$pass
Full Name The Karten
208
34666666666
34966666666
This is the Karten
EOF
echo "armando:$pass" >> /root/.back_credentials


user_flag="armando"
cat <<EOF > /var/www/ssh_credentials.md
**TODO: Resolver el enigma de "$user_flag" para obtener su credencial**
El terreno está preparado para el combate, donde el sonido de los pasos resonará en cada rincón.
Un tambor golpea implacable, marcando el paso de una multitud que avanza con fuerza. La vibra de
una era de gloria se enciende, y el eco de unos puños al unísono prepara la lucha. El visitante 
no está solo: se une a miles, y juntos desafían lo imposible.

En cada rincón, las sombras se dispersan y la verdad se alza: aquellos que nunca se rindieron
están listos para hacer temblar el suelo. La multitud sigue la llamada, sus pies golpeando al
ritmo del tambor. El desafío está planteado: ¿estás listo para unirte a la batalla?

El poder no está en lo que temes, sino en lo que eres capaz de conquistar. La fortaleza no
proviene de muros, sino de la unidad, el rugido de la multitud, y el grito de guerra que te
empuja a seguir adelante. Encuentra tu fuerza, sigue el ritmo, y sabrás qué se necesita para
triunfar. Recuerda: el tambor nunca deja de sonar. Y con un poco de fuerza brutal y un toque de
sabiduría, la victoria será tuya.
EOF

echo 'flag{570dd991217570f3a8dc417d00372183}' > /root/root.txt
echo 'flag{293aea10df6464942b3cce268c9c75af}' > /home/$user_flag/user.txt

apt update -y
apt install sudo gcc -y

# Create SUID file
echo "I2luY2x1ZGUgPHN0ZGlvLmg+CiNpbmNsdWRlIDx1bmlzdGQuaD4KCmludCBtYWluKCkgewogICAgcHJpbnRmKCJFamVjdXRhbmRvIGNvbiBwcml2aWxlZ2lvcyBkZSBTVUlELlxuIik7CiAgICBzZXR1aWQoMCk7ICAvLyBDYW1iaWEgZWwgSUQgZGUgdXN1YXJpbyBlZmVjdGl2byBhIHJvb3QKICAgIHNldGdpZCgwKTsgIC8vIENhbWJpYSBlbCBJRCBkZSBncnVwbyBlZmVjdGl2byBhIHJvb3QKICAgIGV4ZWNsKCIvYmluL2Jhc2giLCAiYmFzaCIsIE5VTEwpOyAgLy8gRWplY3V0YSBiYXNoIGNvbiBwcml2aWxlZ2lvcwogICAgcmV0dXJuIDA7Cn0K" | base64 -d > /home/$user_flag/suid.c
gcc -o /home/$user_flag/do_not_execute /home/$user_flag/suid.c
chown root:root /home/$user_flag/do_not_execute
chmod 4111 /home/$user_flag/do_not_execute
rm /home/$user_flag/suid.c

# Add user permissions
gcc -o /home/$user_flag/hola hola.c
echo "$user_flag ALL=(ALL) NOPASSWD: /home/$user_flag/hola" >> /etc/sudoers

chown www-data:www-data /var/www/html/ -R
chmod www-data /var/www/.env















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
# mkdir /var/www/$(awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2;)
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
# mariadb -e "CREATE DATABASE $( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2; );"
# mariadb -e "CREATE USER '$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2; )'@'%' IDENTIFIED BY '$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2; )';"
# mariadb -e "GRANT ALL ON $( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2; ).* TO '$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2; )'@'%';"
# mariadb -e "FLUSH PRIVILEGES;"

# Clean up
echo "Cleaning up..."
# rm /var/www/$( awk 'NR==8' /var/www/credentials.cfg | cut -d'=' -f2; )/info.php


echo "ServerName localhost" >> /etc/apache2/apache2.conf
mkdir -p /var/log/apache2/ctf.com/
chown -R www-data:www-data /var/log/apache2/ctf.com/
chmod -R 750 /var/log/apache2/ctf.com/
service apache2 restart


# Config .env file
sed -i "s/nombre_de_tu_mariadbase_de_datos/$( awk 'NR==7' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/ctf.com/../.env
sed -i "s/nombre_de_usuario_de_la_base_de_datos/$( awk 'NR==4' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/ctf.com/../.env
sed -i "s/contraseña_de_la_base_de_datos/$( awk 'NR==5' /var/www/credentials.cfg | cut -d'=' -f2;)/g" /var/www/ctf.com/../.env

echo "Installation and Configuration completed!"

service apache2 restart &> /dev/null
# service mariadb restart &> /dev/null

# mariadb < /var/www/crear_tablas.sql

echo "Habilitando autoarranque de servicios..."
update-rc.d apache2 defaults
# update-rc.d mariadb defaults
mv /var/www/ctf.com/ /var/www/html/
rm /var/www/{credentials.cfg,EasySetup.sh,do_not_execute,rockyou.txt,DockerFile}