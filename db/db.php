<?php
// Veritabanı bağlantı parametreleri
$host = 'localhost'; // Veritabanı sunucusu
$dbname = 'odev_db'; // Veritabanı adı
$username = 'root'; // Veritabanı kullanıcı adı
$password = ''; // Veritabanı şifresi

// PDO bağlantısı kurma (Hata yönetimi ve SQL injection'a karşı korunma için)
try {
    // PDO ile veritabanı bağlantısı sağlanıyor
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Hata modunu ayarlıyoruz, istisnaları (exceptions) kullanarak hata raporlaması sağlıyoruz
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Karakter setini UTF-8 olarak ayarlıyoruz
    $pdo->exec("SET NAMES 'utf8'");
    
} catch (PDOException $e) {
    // Bağlantı hatası durumunda mesaj yazdırıyoruz
    echo "Bağlantı hatası: " . $e->getMessage();
    exit;
}

// PDO ile yapılacak tüm sorgularda parametreli sorgular kullanılır
// Bu, SQL injection'a karşı koruma sağlar
?>
