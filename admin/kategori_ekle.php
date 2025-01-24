<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Admin giriş yapmamışsa login sayfasına yönlendirme
    exit;
}

include('../db/db.php'); // Veritabanı bağlantısı
// Kategori ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori_adi = $_POST['kategori_adi'];

    // SQL injection'a karşı korunmak için prepared statements kullanıyoruz
    $stmt = $pdo->prepare("INSERT INTO kategoriler (kategori_adi) VALUES (:kategori_adi)");
    $stmt->bindParam(':kategori_adi', $kategori_adi, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $mesaj = "Kategori başarıyla eklendi!";
    } else {
        $mesaj = "Bir hata oluştu, kategori eklenemedi.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Ekle - Admin Paneli</title>
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/font-awesome@5.15.3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome ikonu için -->
            <style>
                body {
                    background: url('https://ebubekirbastama.com.tr/circles.webp');
                    background-position: center;
                    color: black;
                }
                .sidebar {
                    height: 100vh;
                    width: 250px;
                    position: fixed;
                    top: 0;
                    left: 0;
                    background-color: #2d3436;
                    color: #fff;
                    padding-top: 20px;
                }
                .sidebar a {
                    color: #dfe6e9;
                    text-decoration: none;
                    display: block;
                    padding: 15px;
                    margin: 10px 0;
                    border-radius: 5px;
                    font-size: 18px;
                }
                .sidebar a:hover {
                    background-color: #636e72;
                }
                .main-content {
                    margin-left: 250px;
                    padding: 20px;
                }
                .card {
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
            </style>
            </head>
            <body>

                <!-- Sol Menü -->
            <div class="sidebar">
                <h3 class="text-center text-white">Admin Paneli</h3>
                <a href="kitap_ekle.php">Kitap Ekleme</a>
                <a href="kategori_ekle.php">Kategori Ekleme</a>
                <a href="kitaplar.php">Kitaplar</a> <!-- Kitaplar listesini gösteren sayfa -->
                <a href="logout.php">Çıkış Yap</a> <!-- Çıkış yapma linki -->
            </div>

            <!-- Ana İçerik -->
            <div class="main-content">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2>Kategori Ekle</h2>
                            <?php if (isset($mesaj)): ?>
                                <div class="alert alert-info" role="alert">
                                    <?php echo $mesaj; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="kategori_adi" class="form-label">Kategori Adı</label>
                                    <input type="text" class="form-control" id="kategori_adi" name="kategori_adi" required>
                                </div>
                                <button type="submit" class="btn btn-success">Kategori Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap 5.3 JS ve Popper.js -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

            </body>
            </html>
