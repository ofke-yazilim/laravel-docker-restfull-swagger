#!/bin/bash
# write to hosts file
echo '# _____' >> /etc/hosts
echo '127.0.0.1  test122.com' >> /etc/hosts

# Run Laravel Command
/usr/bin/php /data/www/artisan cache:clear
/usr/bin/php /data/www/artisan config:clear
/usr/bin/php /data/www/artisan view:clear
/usr/bin/php /data/www/artisan route:clear
echo 'Lütfen database hazırlanırken bekleyiniz.'
echo '1 Dakika kadar bekleyeceksiniz.'
sleep 60
/usr/bin/php /data/www/artisan migrate
#seeds
/usr/bin/php /data/www/artisan db:seed --class=UserSeeder
/usr/bin/php /data/www/artisan db:seed --class=ProductSeeder
# Crontab çalıştırılıyor
crond

