<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Admin giriş yapmamışsa login sayfasına yönlendirme
    exit;
}

include('../db/db.php'); // Veritabanı bağlantısı
// Kitap ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kitap_adi = $_POST['kitap_adi'];
    $kitap_turu = $_POST['kitap_turu'];
    $yazar = $_POST['yazar'];
    $sayfa = $_POST['sayfa'];
    $yili = $_POST['yili'];
    $dili = $_POST['dili'];
    $fiyat = $_POST['fiyat'];
    $barkod = $_POST['barkod'];

    // Resim yükleme kısmı
    if (isset($_FILES['kapak_foto']) && $_FILES['kapak_foto']['error'] == 0) {
        $kapak_foto = $_FILES['kapak_foto'];
        $foto_ad = md5(time() . $kapak_foto['name']) . '.' . pathinfo($kapak_foto['name'], PATHINFO_EXTENSION); // Resim ismini MD5 ile şifrele
        $upload_dir = '../img/'; // Resmin yükleneceği dizin
        $upload_file = $upload_dir . $foto_ad;

        // Dosya türü ve boyutunu kontrol et
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($kapak_foto['type'], $allowed_types) && $kapak_foto['size'] <= 5 * 1024 * 1024) { // Maksimum 5MB
            if (move_uploaded_file($kapak_foto['tmp_name'], $upload_file)) {
                $mesaj = "Fotoğraf başarıyla yüklendi!";
            } else {
                $mesaj = "Fotoğraf yüklenemedi!";
            }
        } else {
            $mesaj = "Geçersiz dosya tipi veya dosya boyutu çok büyük!";
        }
    } else {
        $mesaj = "Lütfen bir kitap kapağı resmi yükleyin!";
    }

    // SQL injection'a karşı korunmak için prepared statements kullanıyoruz
    $stmt = $pdo->prepare("INSERT INTO kitaplar (kitap_adi, kitap_turu, yazar, sayfa, yili, dili, fiyat, barkod, kapak_foto) 
                           VALUES (:kitap_adi, :kitap_turu, :yazar, :sayfa, :yili, :dili, :fiyat, :barkod, :kapak_foto)");
    $stmt->bindParam(':kitap_adi', $kitap_adi, PDO::PARAM_STR);
    $stmt->bindParam(':kitap_turu', $kitap_turu, PDO::PARAM_INT);
    $stmt->bindParam(':yazar', $yazar, PDO::PARAM_STR);
    $stmt->bindParam(':sayfa', $sayfa, PDO::PARAM_INT);
    $stmt->bindParam(':yili', $yili, PDO::PARAM_INT);
    $stmt->bindParam(':dili', $dili, PDO::PARAM_STR);
    $stmt->bindParam(':fiyat', $fiyat, PDO::PARAM_STR);
    $stmt->bindParam(':barkod', $barkod, PDO::PARAM_STR);
    $stmt->bindParam(':kapak_foto', $foto_ad, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $mesaj = "Kitap başarıyla eklendi!";
    } else {
        $mesaj = "Bir hata oluştu, kitap eklenemedi.";
    }
}

// Kategorileri çekme
$stmt = $pdo->query("SELECT * FROM kategoriler");
$kategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Ekle - Admin Paneli</title>
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
                            <h2>Kitap Ekle</h2>
                            <?php if (isset($mesaj)): ?>
                                <div class="alert alert-info" role="alert">
                                    <?php echo $mesaj; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="kitap_adi" class="form-label">Kitap Adı</label>
                                    <input type="text" class="form-control" id="kitap_adi" name="kitap_adi" required>
                                </div>

                                <div class="mb-3">
                                    <label for="kitap_turu" class="form-label">Kitap Türü</label>
                                    <select class="form-select" id="kitap_turu" name="kitap_turu" required>
                                        <option value="">Kategori Seçin</option>
                                        <?php foreach ($kategoriler as $kategori): ?>
                                            <option value="<?php echo $kategori['kategori_id']; ?>"><?php echo $kategori['kategori_adi']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="yazar" class="form-label">Yazar</label>
                                    <input type="text" class="form-control" id="yazar" name="yazar" required>
                                </div>

                                <div class="mb-3">
                                    <label for="sayfa" class="form-label">Sayfa Sayısı</label>
                                    <input type="number" class="form-control" id="sayfa" name="sayfa" required>
                                </div>

                                <div class="mb-3">
                                    <label for="yili" class="form-label">Yıl</label>
                                    <input type="number" class="form-control" id="yili" name="yili" required>
                                </div>

                                <div class="mb-3">
                                    <label for="dili" class="form-label">Dil</label>
                                    <input type="text" class="form-control" id="dili" name="dili" required>
                                </div>

                                <div class="mb-3">
                                    <label for="fiyat" class="form-label">Fiyat</label>
                                    <input type="number" class="form-control" id="fiyat" name="fiyat" step="0.01" required>
                                </div>

                                <div class="mb-3">
                                    <label for="barkod" class="form-label">Barkod</label>
                                    <input type="text" class="form-control" id="barkod" name="barkod" required>
                                </div>

                                <div class="mb-3">
                                    <label for="kapak_foto" class="form-label">Kitap Fotoğrafı</label>
                                    <input type="file" class="form-control" id="kapak_foto" name="kapak_foto" required>
                                </div>

                                <button type="submit" class="btn btn-success">Kitap Ekle</button>
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
