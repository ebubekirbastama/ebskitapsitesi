<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Admin giriş yapmamışsa login sayfasına yönlendirme
    exit;
}

include('../db/db.php'); // Veritabanı bağlantısı
// Kitap bilgilerini çekme (Güncelleme için mevcut bilgileri göstermek)
if (isset($_GET['kitap_id'])) {
    $kitap_id = $_GET['kitap_id'];

    $stmt = $pdo->prepare("SELECT * FROM kitaplar WHERE kitap_id = :kitap_id");
    $stmt->bindParam(':kitap_id', $kitap_id, PDO::PARAM_INT);
    $stmt->execute();
    $kitap = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kitap) {
        die("Kitap bulunamadı!");
    }
} else {
    die("Geçersiz istek!");
}

// Kitap güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kitap_adi = $_POST['kitap_adi'];
    $kitap_turu = $_POST['kitap_turu'];
    $yazar = $_POST['yazar'];
    $sayfa = $_POST['sayfa'];
    $yili = $_POST['yili'];
    $dili = $_POST['dili'];
    $fiyat = $_POST['fiyat'];
    $barkod = $_POST['barkod'];

    // Kapak fotoğrafını güncelleme işlemi
    if (isset($_FILES['kapak_foto']) && $_FILES['kapak_foto']['error'] == 0) {
        $kapak_foto = $_FILES['kapak_foto'];
        $foto_ad = md5(time() . $kapak_foto['name']) . '.' . pathinfo($kapak_foto['name'], PATHINFO_EXTENSION);
        $upload_dir = '../img/';
        $upload_file = $upload_dir . $foto_ad;

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($kapak_foto['type'], $allowed_types) && $kapak_foto['size'] <= 5 * 1024 * 1024) { // Maksimum 5MB
            if (move_uploaded_file($kapak_foto['tmp_name'], $upload_file)) {
                $mesaj = "Yeni fotoğraf yüklendi!";
            } else {
                $mesaj = "Fotoğraf yüklenemedi!";
            }
        } else {
            $mesaj = "Geçersiz dosya tipi veya dosya boyutu çok büyük!";
        }

        // Eski fotoğrafı silme (Opsiyonel)
        if (!empty($kitap['kapak_foto']) && file_exists($upload_dir . $kitap['kapak_foto'])) {
            unlink($upload_dir . $kitap['kapak_foto']);
        }

        $kapak_foto_ad = $foto_ad; // Yeni fotoğraf adı
    } else {
        $kapak_foto_ad = $kitap['kapak_foto']; // Fotoğraf değiştirilmemişse eski fotoğraf adı
    }

    // SQL ile güncelleme işlemi
    $stmt = $pdo->prepare("UPDATE kitaplar 
                           SET kitap_adi = :kitap_adi, kitap_turu = :kitap_turu, yazar = :yazar, 
                               sayfa = :sayfa, yili = :yili, dili = :dili, fiyat = :fiyat, 
                               barkod = :barkod, kapak_foto = :kapak_foto 
                           WHERE kitap_id = :kitap_id");
    $stmt->bindParam(':kitap_adi', $kitap_adi, PDO::PARAM_STR);
    $stmt->bindParam(':kitap_turu', $kitap_turu, PDO::PARAM_INT);
    $stmt->bindParam(':yazar', $yazar, PDO::PARAM_STR);
    $stmt->bindParam(':sayfa', $sayfa, PDO::PARAM_INT);
    $stmt->bindParam(':yili', $yili, PDO::PARAM_INT);
    $stmt->bindParam(':dili', $dili, PDO::PARAM_STR);
    $stmt->bindParam(':fiyat', $fiyat, PDO::PARAM_STR);
    $stmt->bindParam(':barkod', $barkod, PDO::PARAM_STR);
    $stmt->bindParam(':kapak_foto', $kapak_foto_ad, PDO::PARAM_STR);
    $stmt->bindParam(':kitap_id', $kitap_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $mesaj = "Kitap başarıyla güncellendi!";
        header("Location: kitaplar.php"); // Güncellemeden sonra kitap listesine yönlendirme
        exit;
    } else {
        $mesaj = "Güncelleme sırasında bir hata oluştu!";
    }
}

// Kategorileri çekme (Dropdown için)
$stmt = $pdo->query("SELECT * FROM kategoriler");
$kategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Güncelle - Admin Paneli</title>
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
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
    <div class="container mt-5">
        <h2>Kitap Güncelle</h2>
        <?php if (isset($mesaj)): ?>
            <div class="alert alert-info">
                <?php echo $mesaj; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="kitap_adi" class="form-label">Kitap Adı</label>
                <input type="text" class="form-control" id="kitap_adi" name="kitap_adi" value="<?php echo $kitap['kitap_adi']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="kitap_turu" class="form-label">Kitap Türü</label>
                <select class="form-select" id="kitap_turu" name="kitap_turu" required>
                    <option value="">Kategori Seçin</option>
                    <?php foreach ($kategoriler as $kategori): ?>
                        <option value="<?php echo $kategori['kategori_id']; ?>" <?php if ($kategori['kategori_id'] == $kitap['kitap_turu']) echo 'selected'; ?>>
                            <?php echo $kategori['kategori_adi']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="yazar" class="form-label">Yazar</label>
                <input type="text" class="form-control" id="yazar" name="yazar" value="<?php echo $kitap['yazar']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="sayfa" class="form-label">Sayfa Sayısı</label>
                <input type="number" class="form-control" id="sayfa" name="sayfa" value="<?php echo $kitap['sayfa']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="yili" class="form-label">Yıl</label>
                <input type="number" class="form-control" id="yili" name="yili" value="<?php echo $kitap['yili']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="dili" class="form-label">Dil</label>
                <input type="text" class="form-control" id="dili" name="dili" value="<?php echo $kitap['dili']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="fiyat" class="form-label">Fiyat</label>
                <input type="number" class="form-control" id="fiyat" name="fiyat" step="0.01" value="<?php echo $kitap['fiyat']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="barkod" class="form-label">Barkod</label>
                <input type="text" class="form-control" id="barkod" name="barkod" value="<?php echo $kitap['barkod']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="kapak_foto" class="form-label">Kitap Fotoğrafı</label>
                <input type="file" class="form-control" id="kapak_foto" name="kapak_foto">
                    <img src="../img/<?php echo $kitap['kapak_foto']; ?>" alt="Kapak Fotoğrafı" width="150" class="mt-2">
                        </div>

                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="kitaplar.php" class="btn btn-secondary">İptal</a>
                        </form>
                        </div>
                        </body>
                        </html>
