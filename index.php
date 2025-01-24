<?php
session_start();
include('db/db.php'); // Veritabanı bağlantısı
// Kategoriler
$kategoriler = $pdo->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);

// Son eklenen 5 kitap
$son_eklenen_kitaplar = $pdo->query("SELECT * FROM kitaplar ORDER BY kitap_id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Fiyata göre artan sıralama
$fiyata_gore_kitaplar = $pdo->query("SELECT * FROM kitaplar ORDER BY fiyat ASC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Yazara göre en fazla kitabı olan 5 yazar
$yazarlara_gore_kitaplar = $pdo->query("
    SELECT yazar, COUNT(*) AS kitap_sayisi 
    FROM kitaplar 
    GROUP BY yazar 
    ORDER BY kitap_sayisi DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Tüm kategoriler için son eklenen 5 kitap
$kategoriye_gore_kitaplar = $pdo->query("
    SELECT k.*, c.kategori_adi 
    FROM kitaplar k 
    INNER JOIN kategoriler c ON k.kitap_turu = c.kategori_id 
    ORDER BY k.kitap_id DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Satış Sitesi</title>
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/indexcss.css" rel="stylesheet" type="text/css"/>

    </head>
    <body>

        <?php
        include 'header.php';
        ?>

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

            <!-- Sağ Taraf: Kitap Kartları -->
            <div class="col-md-9">

                <!-- Modern Slider -->
                <div class="">
                    <div id="modernSlider" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            // Slider klasöründeki resim dosyalarını alıyoruz
                            $slider_resimleri = glob('slider/s*.webp');
                            foreach ($slider_resimleri as $index => $resim):
                                ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo htmlspecialchars($resim); ?>" 
                                         class="d-block w-100" 
                                         alt="Slider Resim <?php echo $index + 1; ?>" 
                                         style="width:100%; object-fit: cover; object-position: center;">
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>Slider Resim <?php echo $index + 1; ?></h5>
                                        </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev custom-control" type="button" data-bs-target="#modernSlider" data-bs-slide="prev">
                            <span class="custom-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Önceki</span>
                        </button>
                        <button class="carousel-control-next custom-control" type="button" data-bs-target="#modernSlider" data-bs-slide="next">
                            <span class="custom-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Sonraki</span>
                        </button>
                    </div>
                </div>


                <!-- Son Eklenen 5 Kitap -->
                <h2 class="section-title">Son Eklenen Kitaplar</h2>
                <div class="row">
                    <?php foreach ($son_eklenen_kitaplar as $kitap): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="img/<?php echo htmlspecialchars($kitap['kapak_foto']); ?>" class="card-img-top" alt="Kapak">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($kitap['yazar']); ?></p>
                                        <p class="text-success"><?php echo number_format($kitap['fiyat'], 2); ?> ₺</p>
                                        <a href="kitap_detay.php?kitap_id=<?php echo $kitap['kitap_id']; ?>" class="btn btn-primary btn-sm">Detayları Gör</a>
                                    </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Fiyata Göre Sıralama -->
                <h2 class="section-title">En Ucuz Kitaplar</h2>
                <div class="row">
                    <?php foreach ($fiyata_gore_kitaplar as $kitap): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="img/<?php echo htmlspecialchars($kitap['kapak_foto']); ?>" class="card-img-top" alt="Kapak">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($kitap['yazar']); ?></p>
                                        <p class="text-success"><?php echo number_format($kitap['fiyat'], 2); ?> ₺</p>
                                        <a href="kitap_detay.php?kitap_id=<?php echo $kitap['kitap_id']; ?>" class="btn btn-primary btn-sm">Detayları Gör</a>
                                    </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Kategoriye Göre Son Eklenen 5 Kitap -->
                <h2 class="section-title">Kategoriye Göre Son Eklenen Kitaplar</h2>
                <div class="row">
                    <?php foreach ($kategoriye_gore_kitaplar as $kitap): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="img/<?php echo htmlspecialchars($kitap['kapak_foto']); ?>" class="card-img-top" alt="Kapak">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($kitap['kitap_adi']); ?></h5>
                                        <p class="card-text">Kategori: <?php echo htmlspecialchars($kitap['kategori_adi']); ?></p>
                                        <p class="text-success"><?php echo number_format($kitap['fiyat'], 2); ?> ₺</p>
                                        <a href="kitap_detay.php?kitap_id=<?php echo $kitap['kitap_id']; ?>" class="btn btn-primary btn-sm">Detayları Gör</a>
                                    </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Yazara Göre En Fazla Kitap -->
                <h2 class="section-title">En Fazla Kitap Yazan Yazarlar</h2>
                <div class="row">
                    <?php foreach ($yazarlara_gore_kitaplar as $yazar): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="yazar.php?yazar=<?php echo urlencode($yazar['yazar']); ?>">
                                            <?php echo htmlspecialchars($yazar['yazar']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text">Toplam Kitap: <?php echo $yazar['kitap_sayisi']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS ve Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
