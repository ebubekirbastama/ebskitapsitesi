<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Admin giriş yapmamışsa login sayfasına yönlendirme
    exit;
}

include('../db/db.php'); // Veritabanı bağlantısı
// Kitapları veritabanından çekme
$stmt = $pdo->query("SELECT k.*, c.kategori_adi FROM kitaplar k 
                     LEFT JOIN kategoriler c ON k.kitap_turu = c.kategori_id 
                     ORDER BY k.kitap_id DESC");
$kitaplar = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitaplar - Admin Paneli</title>
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/font-awesome@5.15.3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome -->
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
                .table img {
                    max-width: 50px;
                    border-radius: 5px;
                }
            </style>
            </head>
            <body>

                <!-- Sol Menü -->
            <div class="sidebar">
                <h3 class="text-center text-white">Admin Paneli</h3>
                <a href="kitaplar.php" class="active">Kitaplar</a>
                <a href="kitap_ekle.php">Kitap Ekleme</a>
                <a href="kategori_ekle.php">Kategori Ekleme</a>
                <a href="logout.php">Çıkış Yap</a> <!-- Çıkış yapma linki -->
            </div>

            <!-- Ana İçerik -->
            <div class="main-content">
                <div class="container mt-5">
                    <h2>Kitap Listesi</h2>
                    <a href="kitap_ekle.php" class="btn btn-success mb-3">Yeni Kitap Ekle</a>
                    <?php if (isset($_GET['mesaj'])): ?>
                        <div class="alert alert-info">
                            <?php echo htmlspecialchars($_GET['mesaj']); ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kitap Adı</th>
                                <th>Türü</th>
                                <th>Yazar</th>
                                <th>Sayfa Sayısı</th>
                                <th>Yıl</th>
                                <th>Dil</th>
                                <th>Fiyat</th>
                                <th>Barkod</th>
                                <th>Kapak Fotoğrafı</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($kitaplar) > 0): ?>
                                <?php foreach ($kitaplar as $kitap): ?>
                                    <tr>
                                        <td><?php echo $kitap['kitap_id']; ?></td>
                                        <td><?php echo htmlspecialchars($kitap['kitap_adi']); ?></td>
                                        <td><?php echo htmlspecialchars($kitap['kategori_adi'] ?: 'Kategori Yok'); ?></td>
                                        <td><?php echo htmlspecialchars($kitap['yazar']); ?></td>
                                        <td><?php echo $kitap['sayfa']; ?></td>
                                        <td><?php echo $kitap['yili']; ?></td>
                                        <td><?php echo htmlspecialchars($kitap['dili']); ?></td>
                                        <td><?php echo number_format($kitap['fiyat'], 2); ?> ₺</td>
                                        <td><?php echo htmlspecialchars($kitap['barkod']); ?></td>
                                        <td>
                                            <?php if (!empty($kitap['kapak_foto'])): ?>
                                    <img src="../img/<?php echo htmlspecialchars($kitap['kapak_foto']); ?>" alt="Kapak Fotoğrafı">
                                    <?php else: ?>
                                        <span>Fotoğraf Yok</span>
                                    <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="kitap_guncelle.php?kitap_id=<?php echo $kitap['kitap_id']; ?>" class="btn btn-primary btn-sm">Düzenle</a>
                                        <a href="kitap_sil.php?kitap_id=<?php echo $kitap['kitap_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu kitabı silmek istediğinize emin misiniz?');">Sil</a>
                                    </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center">Henüz kayıtlı kitap yok.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                    </table>
                </div>
            </div>

            <!-- Bootstrap 5.3 JS ve Popper.js -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

            </body>
            </html>
