# Hakkında
 - **Laravel 8** Framework'ü üzerine **Php 7.4** ile kodlanmıştır.
 - Docker için **dockerfile** ve **docker-compose.yaml** dosyaları oluşturulmuştur.
 - İçerisinde Restfull servisleri barındırmaktadır.
 - Kodlanan servislerin dökümantasyonu https://swagger.io/ altyapısına entegre edilmiştir.
# Docker Kurulumu
 - Docker image oluşturmak için Dockerfile'ın bulunduğu dizinde aşağıdaki command çalıştırılmalıdır.
`docker build -t image:1.0.1`
 - İmage oluşturulduktan sonra **Docker-compose.yaml** dosyası açılarak dosya içerisine oluşturulan versiyon yazılır.
 - Aşağıdaki command çalıştırılarak. Docker ortamda proje ayağı kaldırılmış olur.
  `docker-compose up -d --build`
> Yukarıdaki işlemlerden sonra 1 kaç dakika beklenmelidir. Mysql veritabanın ve Projenin ayağı kalkması biraz zaman alıyor.

İşlem local bilgisayarda yapıldıysa projeye ait linkler aşağıdadır.
**Servisler  ve Kullanımları :** https://localhost/api/documentation (Servsler bu adresten test edilebilir.)
**Phpmyadmin:**   http://localhost:8080/ (**username:** root **password:** Z5AajEapuLZuNuv)  

 - Projenin bulunduğu container içerisine girebilmek için aşağıdaki
   command çalıştırılmalıdır. 
   `docker-compose exec web sh` 
   
 - Yukarıdaki commad ile container terminaline girilmiş olur. Proje
   kodlarının     bulunduğu yere ise`cd data/www` dosya yoluna gidilerek ulaşılır.

StackEdit stores your files in your browser, which means all your files are automatically saved locally and are accessible **offline!**

## Dosyalar

