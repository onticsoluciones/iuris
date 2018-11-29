# iuris
Herramienta de auditoría de sitios web para verificación del cumplimiento de legislaciones como la LOPD, RGPD y LSSI de forma autónoma, sin depender de terceros, y complementada con una guía de consejos para cumplirlas.

# Requirements

- PHP 5+
- MySQL
- Java 1.8+
- Latest version of Google Chrome

# Quick start

##### Download ChromeDriver and place it inside $PATH

cd /tmp  
curl "https://chromedriver.storage.googleapis.com/2.44/chromedriver_linux64.zip" | jar xv  
sudo mv chromedriver /usr/local/bin/chromedriver

##### Download & launch Selenium Standalone Server

cd ~/iuris  
wget https://selenium-release.storage.googleapis.com/3.141/selenium-server-standalone-3.141.59.jar  
java -jar selenium-server-standalone-3.141.59.jar

##### Create an empty MySQL database

mysql -e "create database iuris; grant all privileges on iuris.* to 'iuris'@'localhost' identified by 'yourpassword';"

##### Clone the repository

cd ~  
git clone https://github.com/onticsoluciones/iuris.git

##### Run composer on the backend directory to fetch its dependencies

cd ~/iuris/backend  
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"  
php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"  
php composer-setup.php  
php -r "unlink('composer-setup.php');"  
php composer install  

##### Set the database configuration

cp ~/iuris/backend/parameters.yml.dist ~/iuris/backend/parameters.yml  
cp ~/iuris/backend/phinx.yml.dist ~/iuris/backend/phinx.yml

Edit the .yml files to set your database configuration.

##### Create the database schema

cd ~/iuris/backend  
vendor/bin/phinx migrate

##### Start the backend using the integrated PHP server

cd ~/iuris/backend  
php -S localhost:8080

##### Start the backend using the integrated PHP server

cd ~/iuris/frontend  
php -S localhost:8081
