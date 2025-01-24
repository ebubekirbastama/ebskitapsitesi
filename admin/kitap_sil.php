<?php
session_start();
include('../db/db.php'); // Veritabanı bağlantısı

// Kitap ID'si URL'den alınıyor
if (isset($_GET['kitap_id'])) {
    $kitap_id = filter_input(INPUT_GET, 'kitap_id', FILTER_SANITIZE_NUMBER_INT);

    // Veritabanından kitap silme
    try {
        $stmt = $pdo->prepare("DELETE FROM kitaplar WHERE kitap_id = :kitap_id");
        $stmt->bindParam(':kitap_id', $kitap_id, PDO::PARAM_INT);
        $stmt->execute();

        // Silme işlemi başarılı
        $_SESSION['message'] = 'Kitap başarıyla silindi.';
        header('Location: kitaplar.php'); // Ana sayfaya yönlendirme
    } catch (Exception $e) {
        // Hata durumu
        $_SESSION['error'] = 'Hata oluştu: ' . $e->getMessage();
        header('Location: kitaplar.php');
    }
} else {
    // Kitap ID'si gönderilmemişse hata mesajı
    $_SESSION['error'] = 'Kitap ID belirtilmemiş.';
    header('Location: kitaplar.php');
}
?>
