<!-- header.php -->
<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary">
    <div class="container">
        <a class="navbar-brand" href="">Giriş</a>
        <a class="navbar-brand" href="">Kayıt Ol</a>
        <div class="ms-auto">
            <a href="admin/login.php" class="btn btn-outline-light">Yönetici Girişi</a>
        </div>
    </div>
</nav>
<!-- Arama Alanı -->
<div class="search-container py-3 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-12 col-md-2 text-center text-md-start mb-3 mb-md-0">
                <a href="index.php">
                    <img src="img/logo.png" alt="Site Logo" class="img-fluid" style="max-height: 60px;">
                </a>
            </div>
            <!-- Arama Kutusu ve Buton -->
            <div class="col-12 col-md-10">
                <form action="arama_sonuclari.php" method="get" class="d-flex">
                    <input 
                        type="text" 
                        name="ara" 
                        class="form-control me-2" 
                        placeholder="Kitap Ara..." 
                        required 
                        style="max-width: 80%;">
                        <button type="submit" class="btn btn-primary">Ara</button>
                </form>
            </div>
        </div>
    </div>
</div>
