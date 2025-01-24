<?php
session_start();
include('db/db.php'); // Veritabanı bağlantısı
// Kategoriler
$kategoriler = $pdo->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);

// Kitap bilgilerini almak
if (isset($_GET['kitap_id'])) {
    $kitap_id = filter_input(INPUT_GET, 'kitap_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $pdo->prepare("SELECT * FROM kitaplar WHERE kitap_id = :kitap_id");
    $stmt->bindParam(':kitap_id', $kitap_id, PDO::PARAM_INT);
    $stmt->execute();
    $kitap = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$kitap) {
        die("Kitap bulunamadı! Lütfen geçerli bir kitap ID'si giriniz.");
    }
} else {
    die("Kitap ID belirtilmemiş!");
}

// Yorumlar
$yorumlar_stmt = $pdo->prepare("SELECT * FROM yorumlar WHERE kitap_id = :kitap_id ORDER BY tarih DESC");
$yorumlar_stmt->bindParam(':kitap_id', $kitap_id, PDO::PARAM_INT);
$yorumlar_stmt->execute();
$yorumlar = $yorumlar_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Detayları - <?php echo htmlspecialchars($kitap['kitap_adi']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: url('https://ebubekirbastama.com.tr/circles.webp');
                background-position: center;
                color: black;
            }
            .navbar {
                background-color: #2d3436;
            }
            .navbar a {
                color: #dfe6e9;
            }
            .navbar a:hover {
                color: #00cec9;
            }
            .card img {
                height: 200px;
                object-fit: cover;
            }
            .card-title {
                font-size: 1.2rem;
                font-weight: bold;
            }
            .section-title {
                border-bottom: 2px solid #2d3436;
                margin-bottom: 20px;
                font-size: 1.5rem;
                font-weight: bold;
            }
            .sidebar {
                background-color: #ffffff;
                padding: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            .sidebar h4 {
                font-size: 1.2rem;
                margin-bottom: 15px;
            }
            .sidebar ul {
                list-style: none;
                padding: 0;
            }
            .sidebar ul li {
                margin-bottom: 10px;
            }
            .sidebar ul li a {
                color: #2d3436;
                text-decoration: none;
            }
            .sidebar ul li a:hover {
                color: #00cec9;
            }
            .search-bar {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                margin-top: 20px;
            }

            .search-bar input {
                width: 60%;
                max-width: 500px;
            }
        </style>
    </head>
    <body>


        <?php include 'header.php'; ?>


        <!-- Ana İçerik -->
    <div class="container mt-3">
        <div class="row">
            <!-- Sol Taraf: Kategori Listesi -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h4>Kategoriler</h4>
                    <ul>
                        <?php foreach ($kategoriler as $kategori): ?>
                            <li>
                                <a href="kategori.php?kategori_id=<?php echo $kategori['kategori_id']; ?>">
                                    <?php echo htmlspecialchars($kategori['kategori_adi']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Sağ Taraf: Kitap Detayları ve Yorumlar -->
            <div class="col-md-9">
                <h2 class="section-title"><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h2>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Kitap Kapağı -->
                        <img src="img/<?php echo htmlspecialchars($kitap['kapak_foto']); ?>" class="img-fluid" alt="Kitap Kapağı">
                    </div>

                    <div class="col-md-6">
                        <!-- Kitap Bilgileri -->
                        <h5>Yazar: <?php echo htmlspecialchars($kitap['yazar']); ?></h5>
                        <p><strong>Barcode:</strong> <?php echo $kitap['barkod']; ?></p>
                        <p><strong>Sayfa Sayısı:</strong> <?php echo $kitap['sayfa']; ?></p>
                        <p><strong>Yıl:</strong> <?php echo $kitap['yili']; ?></p>
                        <p><strong>Dil:</strong> <?php echo $kitap['dili']; ?></p>
                        <p><strong>Kategorisi:</strong> <?php echo $kitap['kitap_turu']; ?></p>
                        <p><strong>Fiyat:</strong> <?php echo number_format($kitap['fiyat'], 2); ?> ₺</p>
                    </div>
                </div>

                <!-- Yorumlar -->
                <h3 class="section-title">Yorumlar</h3>
                <div class="comments-section">
                    <?php if ($yorumlar): ?>
                        <?php foreach ($yorumlar as $yorum): ?>
                            <div class="comment-item">
                                <h5><?php echo htmlspecialchars($yorum['kullanici_adi']); ?> 
                                    <span class="star-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo ($i <= $yorum['puan']) ? "&#9733;" : "&#9734;";
                                        }
                                        ?>
                                    </span>
                                </h5>
                                <p><?php echo nl2br(htmlspecialchars($yorum['yorum_metni'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Henüz yorum yapılmamış.</p>
                    <?php endif; ?>
                </div>

                <!-- Yorum Ekleme Formu -->
                <div class="add-comment">
                    <h3>Yorum Yap</h3>
                    <form action="yorum_ekle.php" method="POST">
                        <div class="mb-3">
                            <label for="ad_soyad" class="form-label">Ad Soyad:</label>
                            <input type="text" class="form-control" id="ad_soyad" name="ad_soyad" required>
                        </div>

                        <div class="mb-3">
                            <label for="puan" class="form-label">Puan (1-5):</label>
                            <select class="form-select" id="puan" name="puan" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="yorum" class="form-label">Yorumunuz:</label>
                            <textarea class="form-control" id="yorum" name="yorum" rows="4" required></textarea>
                        </div>

                        <!-- Kitap ID (Hidden Input) -->
                        <input type="hidden" name="kitap_id" value="<?php echo $kitap_id; ?>">

                            <button type="submit" class="btn btn-primary">Yorum Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
