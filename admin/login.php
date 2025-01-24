<?php
session_start();
include('../db/db.php'); // Veritabanı bağlantısını dahil ediyoruz
// Giriş işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullanici_adi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    // SQL injection'a karşı korunmak için prepared statements kullanıyoruz
    $stmt = $pdo->prepare("SELECT * FROM admin_kullanicilar WHERE kullanici_adi = :kullanici_adi");
    $stmt->bindParam(':kullanici_adi', $kullanici_adi, PDO::PARAM_STR);
    $stmt->execute();

    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    // Şifreyi doğrulama (düz metin karşılaştırma)
    if ($kullanici && $kullanici['sifre'] === $sifre) {
        $_SESSION['admin'] = $kullanici['id'];
        header("Location: dashboard.php"); // Giriş başarılıysa admin paneline yönlendirme
        exit;
    } else {
        $hata = "Geçersiz kullanıcı adı veya şifre.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş</title>
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-image: linear-gradient(120deg, #3498db, #8e44ad);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .login-container {
                background-color: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
            }
        </style>
    </head>
    <body>

    <div class="login-container">
        <h2 class="text-center">Admin Giriş</h2>

        <?php if (isset($hata)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $hata; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="kullanici_adi" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" required>
            </div>
            <div class="mb-3">
                <label for="sifre" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="sifre" name="sifre" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
        </form>
    </div>

    <!-- Bootstrap 5.3 JS ve Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
