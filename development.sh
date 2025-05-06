#!/bin/bash

# Update the system
sudo yum update -y

# Enable PHP 8.1
sudo amazon-linux-extras enable php8.1
sudo yum clean metadata
sudo yum install php php-common php-cli php-mbstring php-xml php-bcmath php-curl php-mysqlnd php-pdo -y

# Install Composer
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Apache
sudo yum install httpd -y
sudo systemctl start httpd
sudo systemctl enable httpd

# Install Git and required PHP extensions
sudo dnf install git -y
sudo dnf install php-gd php-zip -y

# Navigate to your Laravel app directory and install dependencies
cd /home/ec2-user/ananta-laravel
composer install

# Set Apache Virtual Host
sudo tee /etc/httpd/conf.d/laravel.conf > /dev/null <<EOF
<VirtualHost *:80>
    DocumentRoot /home/ec2-user/ananta-laravel/public
    <Directory /home/ec2-user/ananta-laravel/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

# Enable .htaccess override
sudo sed -i '/<Directory "\/var\/www\/html">/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/httpd/conf/httpd.conf

# Restart Apache
sudo systemctl restart httpd

# Set permissions for Laravel
sudo chown -R apache:apache /home/ec2-user/ananta-laravel
sudo chmod -R 755 /home/ec2-user/ananta-laravel
sudo chmod -R 775 /home/ec2-user/ananta-laravel/storage
sudo chmod -R 775 /home/ec2-user/ananta-laravel/bootstrap/cache
sudo chmod o+x /home /home/ec2-user /home/ec2-user/ananta-laravel

# Restart Apache again to apply changes
sudo systemctl restart httpd

# Set up environment file
cat > /home/ec2-user/ananta-laravel/.env <<EOF
APP_NAME='streamit'
APP_ENV=local
APP_KEY=base64:MLO63StzW01HD0gYPs1BlgpY8vGMtfvZEV7KQ2TBgF8=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://13.232.138.146
MIX_ASSET_URL=http://13.232.138.146

IS_FAKE_DATA=true
IS_DUMMY_DATA=true
IS_DUMMY_DATA_IMAGE=true
IS_DEMO=true

DB_CONNECTION=mysql
DB_HOST=3.97.66.72
DB_PORT=3306
DB_DATABASE=ananta
DB_USERNAME=admin
DB_PASSWORD=jaH1511rds

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
ACTIVE_STORAGE=local
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
EOF

echo "Laravel setup complete. Please run 'php artisan key:generate' manually if needed."
