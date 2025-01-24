<?php
session_start();
session_unset(); // Tüm oturum verilerini temizle
session_destroy(); // Oturum bilgisini yok et
header("Location: login.php"); // Login sayfasına yönlendir
exit;
?>
