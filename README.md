# Hakkında
 - **Laravel 8** Framework'ü üzerine **Php 7.4** ile kodlanmıştır.
 - Docker için **dockerfile** ve **docker-compose.yaml** dosyaları oluşturulmuştur.
 - İçerisinde Restfull servisleri barındırmaktadır.
 - Kodlanan servislerin dökümantasyonu https://swagger.io/ altyapısına entegre edilmiştir.
 - Canlı test için : http://restfull.okesmez.com/api/documentation 
 - Local test için : http://localhost/api/documentation <br><br>
**http://restfull.okesmez.com/api/auth/login ve http://localhost/api/auth/login servisleri kullanılarak token alırken aşağıdaki bilgiler kullanılmalıdır.**
 <pre>
 Email : info@okesmez.com
 Password : 123456
 </pre>
# Docker Kurulumu
İlk olarak Framework dizininde bulunan **.env.example** dosyasının adı **.env** olarak değiştirilmelidir.
****
 - Docker image oluşturmak için Dockerfile'ın bulunduğu dizinde aşağıdaki command çalıştırılmalıdır.<br>
`docker build -t image:1.0.1 .`
 - İmage oluşturulduktan sonra **Docker-compose.yaml** dosyası açılarak dosya içerisine oluşturulan versiyon yazılır.<br>
 - Aşağıdaki command çalıştırılarak. Docker ortamda proje ayağı kaldırılmış olur.<br>
  `docker-compose up -d --build`
> Yukarıdaki işlemlerden sonra birkaç dakika beklenmelidir. Mysql veritabanın ve Projenin ayağı kalkması biraz zaman alıyor.

###  İşlem local bilgisayarda yapılıyorsa projeye ait linkler aşağıdadır.
**Servisler ve Kullanımları :** https://localhost/api/documentation (Servisler bu adresten test edilebilir.)<br>
**Phpmyadmin:**   http://localhost:8080/ (**username:** root **password:** Z5AajEapuLZuNuv)  

 - Projenin bulunduğu container içerisine girebilmek için aşağıdaki command çalıştırılmalıdır. <br>
   `docker-compose exec web sh` 
   
 - Yukarıdaki commad ile container terminaline girilmiş olur. Proje kodlarının  bulunduğu yere ise`cd data/www` dosya yoluna gidilerek ulaşılır.

## Postman

 - Localhost üzerinde servisleri çalıştırmak için postman üzerine import edilecek olan dosya aşağıdadır.<br>
https://github.com/ofke-yazilim/laravel-restfull-swagger/blob/main/local-restfull.postman_collection.json
- okesmez.com üzerinde çalışan projeyi postman üzerinde test etmek için ise aşağıdaki dosya import edilmelidir.<br>
https://github.com/ofke-yazilim/laravel-restfull-swagger/blob/main/okesmez.com-restfull.postman_collection.json

## Dosyalar

 1. Route : https://github.com/ofke-yazilim/laravel-restfull-swagger/blob/main/framework/routes/api.php
 2. Controller : https://github.com/ofke-yazilim/laravel-restfull-swagger/tree/main/framework/app/Http/Controllers
 3. Model : https://github.com/ofke-yazilim/laravel-restfull-swagger/tree/main/framework/app/Models
 4. Seed : https://github.com/ofke-yazilim/laravel-restfull-swagger/tree/main/framework/database/seeders
 5. Migrations : https://github.com/ofke-yazilim/laravel-restfull-swagger/tree/main/framework/database/migrations
 6. Discount Helper : https://github.com/ofke-yazilim/laravel-restfull-swagger/tree/main/framework/app/Helpers

> Siparişlere ait indirim kurallarını takip eden Discount sınıfına yeni bir fonksiyon oluşturularak yeni bir indirim kuralı koyulabilir.


