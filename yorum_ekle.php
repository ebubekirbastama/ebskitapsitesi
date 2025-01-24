<?php
session_start();
include('db/db.php'); // Veritabanı bağlantısını dahil et

// Formdan gelen veriyi kontrol et
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form verilerini al
    $ad_soyad = htmlspecialchars($_POST['ad_soyad']);  // Kullanıcı adı (Ad ve Soyad)
    $puan = (int) $_POST['puan'];  // Kullanıcının verdiği puan
    $yorum = htmlspecialchars($_POST['yorum']);  // Yorum metni
    $kitap_id = (int) $_POST['kitap_id'];  // Kitap ID'si

    // Veritabanına yorum ekle
    if ($ad_soyad && $puan >= 1 && $puan <= 5 && $yorum && $kitap_id) {
        // Veritabanına yeni yorum ekle
        $stmt = $pdo->prepare("INSERT INTO yorumlar (kitap_id, kullanici_adi, puan, yorum_metni, tarih) 
                               VALUES (:kitap_id, :kullanici_adi, :puan, :yorum_metni, NOW())");
        $stmt->bindParam(':kitap_id', $kitap_id, PDO::PARAM_INT);
        $stmt->bindParam(':kullanici_adi', $ad_soyad, PDO::PARAM_STR);
        $stmt->bindParam(':puan', $puan, PDO::PARAM_INT);
        $stmt->bindParam(':yorum_metni', $yorum, PDO::PARAM_STR);

        // Yorum ekleme işlemi başarılıysa
        if ($stmt->execute()) {
            // Başarılı işlem sonrası kitap detay sayfasına yönlendir
            header("Location: kitap_detay.php?kitap_id=".$kitap_id);
            exit();
        } else {
            // Hata mesajı, işlem başarısız
            echo "Yorum eklenirken bir hata oluştu.";
        }
    } else {
        // Hata mesajı, geçersiz veri
        echo "Geçersiz veri. Lütfen tüm alanları doğru şekilde doldurduğunuzdan emin olun.";
    }
} else {
    // Geçersiz istek
    echo "Geçersiz istek.";
}

?>
