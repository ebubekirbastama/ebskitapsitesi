<?php
session_start();
include('db/db.php'); // Veritabanı bağlantısı
// Kategoriler
$kategoriler = $pdo->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['yazar'])) {
    $yazar_adi = $_GET['yazar'];

    // Seçili yazara göre kitapları getirme
    $yazar_kitaplari = $pdo->prepare("SELECT * FROM kitaplar WHERE yazar = :yazar");
    $yazar_kitaplari->execute(['yazar' => $yazar_adi]);
    $kitaplar = $yazar_kitaplari->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: index.php"); // Eğer yazar bilgisi yoksa ana sayfaya yönlendir
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($yazar_adi); ?> Kitapları</title>
    <!-- Bootstrap 5.3 CDN -->
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
        </style>
    </head>
    <body>

        <?php include 'header.php'; ?>

        <!-- Ana İçerik -->
    <div class="container mt-4">
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

            <!-- Sağ Taraf: Yazar Kitapları -->
            <div class="col-md-9">
                <h1 class="section-title"><?php echo htmlspecialchars($yazar_adi); ?> Tarafından Yazılan Kitaplar</h1>
                <div class="row mt-3">
                    <?php if (!empty($kitaplar)): ?>
                        <?php foreach ($kitaplar as $kitap): ?>
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <img src="img/<?php echo htmlspecialchars($kitap['kapak_foto']); ?>" class="card-img-top" alt="Kapak">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h5>
                                            <p class="card-text">Fiyat: <?php echo number_format($kitap['fiyat'], 2); ?> ₺</p>
                                            <a href="kitap_detay.php?kitap_id=<?php echo $kitap['kitap_id']; ?>" class="btn btn-primary btn-sm">Detayları Gör</a>
                                        </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Bu yazara ait kitap bulunamadı.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS ve Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
